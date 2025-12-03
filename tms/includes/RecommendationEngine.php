<?php
/**
 * TOURISM MANAGEMENT SYSTEM - RECOMMENDATION ENGINE
 * BCA 8th Semester Project
 * 
 * Package Recommendation Algorithm (Collaborative Filtering & Content-Based Filtering)
 * Enhanced with better scoring and more diverse recommendations
 */

class RecommendationEngine {
    private $dbh;
    
    public function __construct($database) {
        $this->dbh = $database;
    }
    
    /**
     * Get recommended packages for a user based on:
     * - User's booking history
     * - Similar users' bookings (Collaborative Filtering)
     * - Package type and location similarity (Content-Based)
     * - Popularity and trending factors
     */
    public function getRecommendedPackages($userEmail, $limit = 4) {
        $recommendations = [];
        
        // Step 1: Get user's booking history
        $userHistory = $this->getUserBookingHistory($userEmail);
        
        if (empty($userHistory)) {
            // New user - return popular packages
            return $this->getPopularPackages($limit);
        }
        
        // Step 2: Find similar users using Jaccard Similarity
        $similarUsers = $this->findSimilarUsers($userEmail, $userHistory);
        
        // Step 3: Get packages booked by similar users
        $collaborativeRecs = $this->getCollaborativeRecommendations($similarUsers, $userHistory);
        
        // Step 4: Get content-based recommendations
        $contentRecs = $this->getContentBasedRecommendations($userHistory);
        
        // Step 5: Get trending packages
        $trendingRecs = $this->getTrendingPackages();
        
        // Step 6: Merge and rank recommendations with weighted scoring
        $recommendations = $this->mergeRecommendationsWithScoring(
            $collaborativeRecs, 
            $contentRecs, 
            $trendingRecs,
            $userHistory,
            $limit
        );
        
        return $recommendations;
    }
    
    private function getUserBookingHistory($userEmail) {
        $sql = "SELECT DISTINCT PackageId FROM tblbooking WHERE UserEmail = :email";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Find similar users using Jaccard Similarity Coefficient
     * Jaccard Similarity = (A ∩ B) / (A ∪ B)
     */
    private function findSimilarUsers($currentUser, $userHistory) {
        $sql = "SELECT UserEmail, GROUP_CONCAT(DISTINCT PackageId) as packages 
                FROM tblbooking 
                WHERE UserEmail != :email 
                GROUP BY UserEmail";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':email', $currentUser, PDO::PARAM_STR);
        $query->execute();
        $allUsers = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $similarities = [];
        
        foreach ($allUsers as $user) {
            $otherPackages = explode(',', $user['packages']);
            
            // Calculate Jaccard Similarity
            $intersection = count(array_intersect($userHistory, $otherPackages));
            $union = count(array_unique(array_merge($userHistory, $otherPackages)));
            
            if ($union > 0) {
                $similarity = $intersection / $union;
                
                // Increased threshold for better quality matches
                if ($similarity > 0.4) { // Threshold: 40% similarity
                    $similarities[$user['UserEmail']] = [
                        'similarity' => $similarity,
                        'packages' => $otherPackages
                    ];
                }
            }
        }
        
        // Sort by similarity score (descending)
        uasort($similarities, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        
        return array_slice($similarities, 0, 5, true); // Top 5 similar users
    }
    
    private function getCollaborativeRecommendations($similarUsers, $userHistory) {
        if (empty($similarUsers)) {
            return [];
        }
        
        $recommendations = [];
        
        // Weight recommendations by similarity score
        foreach ($similarUsers as $userEmail => $userData) {
            $similarity = $userData['similarity'];
            $packages = $userData['packages'];
            
            foreach ($packages as $pkgId) {
                if (!in_array($pkgId, $userHistory)) {
                    if (!isset($recommendations[$pkgId])) {
                        $recommendations[$pkgId] = 0;
                    }
                    $recommendations[$pkgId] += $similarity; // Weight by similarity
                }
            }
        }
        
        // Sort by weighted score
        arsort($recommendations);
        
        return array_keys($recommendations);
    }
    
    /**
     * Content-Based Filtering: Recommend similar packages
     * Based on package type and location with enhanced matching
     */
    private function getContentBasedRecommendations($userHistory) {
        if (empty($userHistory)) {
            return [];
        }
        
        // Get characteristics of user's booked packages
        $placeholders = implode(',', array_fill(0, count($userHistory), '?'));
        
        $sql = "SELECT PackageType, PackageLocation 
                FROM tbltourpackages 
                WHERE PackageId IN ($placeholders)";
        $query = $this->dbh->prepare($sql);
        $query->execute($userHistory);
        $bookedPackages = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Extract preferred types and locations with frequency
        $typeScores = [];
        $locationScores = [];
        
        foreach ($bookedPackages as $pkg) {
            $type = $pkg['PackageType'];
            $location = $pkg['PackageLocation'];
            
            if (!isset($typeScores[$type])) {
                $typeScores[$type] = 0;
            }
            $typeScores[$type]++;
            
            if (!isset($locationScores[$location])) {
                $locationScores[$location] = 0;
            }
            $locationScores[$location]++;
        }
        
        // Sort by frequency (descending)
        arsort($typeScores);
        arsort($locationScores);
        
        // Get top preferences
        $topTypes = array_slice(array_keys($typeScores), 0, 3);
        $topLocations = array_slice(array_keys($locationScores), 0, 3);
        
        // Find packages with similar characteristics
        $typePlaceholders = implode(',', array_fill(0, count($topTypes), '?'));
        $locationPlaceholders = implode(',', array_fill(0, count($topLocations), '?'));
        
        $sql = "SELECT PackageId, 
                CASE 
                    WHEN PackageType IN ($typePlaceholders) THEN 2
                    ELSE 1
                END * 
                CASE 
                    WHEN PackageLocation IN ($locationPlaceholders) THEN 2
                    ELSE 1
                END as relevance_score
                FROM tbltourpackages 
                WHERE PackageId NOT IN ($placeholders)
                ORDER BY relevance_score DESC, RAND()
                LIMIT 10";
        
        $params = array_merge($topTypes, $topLocations, $userHistory);
        $query = $this->dbh->prepare($sql);
        $query->execute($params);
        
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $packageIds = [];
        
        foreach ($results as $row) {
            $packageIds[] = $row['PackageId'];
        }
        
        return $packageIds;
    }
    
    /**
     * Get trending packages based on recent bookings
     */
    private function getTrendingPackages() {
        $sql = "SELECT p.PackageId, COUNT(b.BookingId) as booking_count
                FROM tbltourpackages p
                LEFT JOIN tblbooking b ON p.PackageId = b.PackageId 
                WHERE b.RegDate >= DATE_SUB(NOW(), INTERVAL 60 DAY)
                GROUP BY p.PackageId
                HAVING booking_count > 0
                ORDER BY booking_count DESC
                LIMIT 10";
        
        $query = $this->dbh->prepare($sql);
        $query->execute();
        
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $packageIds = [];
        
        foreach ($results as $row) {
            $packageIds[] = $row['PackageId'];
        }
        
        return $packageIds;
    }
    
    /**
     * Enhanced merge with weighted scoring system
     */
    private function mergeRecommendationsWithScoring($collaborative, $contentBased, $trending, $userHistory, $limit) {
        $scores = [];
        
        // Collaborative filtering weight: 40%
        foreach ($collaborative as $index => $pkgId) {
            if (!isset($scores[$pkgId])) {
                $scores[$pkgId] = 0;
            }
            $scores[$pkgId] += (count($collaborative) - $index) * 0.4;
        }
        
        // Content-based filtering weight: 35%
        foreach ($contentBased as $index => $pkgId) {
            if (!isset($scores[$pkgId])) {
                $scores[$pkgId] = 0;
            }
            $scores[$pkgId] += (count($contentBased) - $index) * 0.35;
        }
        
        // Trending weight: 25%
        foreach ($trending as $index => $pkgId) {
            if (!isset($scores[$pkgId])) {
                $scores[$pkgId] = 0;
            }
            $scores[$pkgId] += (count($trending) - $index) * 0.25;
        }
        
        // Sort by total score (descending)
        arsort($scores);
        
        // Filter out already booked packages and limit results
        $filteredScores = [];
        foreach ($scores as $pkgId => $score) {
            if (!in_array($pkgId, $userHistory)) {
                $filteredScores[$pkgId] = $score;
                if (count($filteredScores) >= $limit) {
                    break;
                }
            }
        }
        
        return array_keys($filteredScores);
    }
    
    /**
     * Get popular packages for new users
     */
    private function getPopularPackages($limit) {
        $sql = "SELECT p.PackageId, COUNT(b.BookingId) as booking_count
                FROM tbltourpackages p 
                LEFT JOIN tblbooking b ON p.PackageId = b.PackageId 
                GROUP BY p.PackageId 
                HAVING booking_count > 0
                ORDER BY booking_count DESC, RAND() 
                LIMIT :limit";
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>