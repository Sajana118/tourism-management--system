<?php
/**
 * TOURISM MANAGEMENT SYSTEM - SMART FILTER
 * BCA 8th Semester Project
 * 
 * Smart Filter Algorithm for Package Recommendations with enhanced filtering
 */

class SmartFilter {
    private $dbh;
    
    public function __construct($database) {
        $this->dbh = $database;
    }
    
    /**
     * Get smart recommendations based on filter criteria
     * @param array $filters - Filter criteria (type, location, price_range)
     * @param int $limit - Maximum number of recommendations
     * @return array - Recommended packages
     */
    public function getSmartRecommendations($filters = [], $limit = 6) {
        // Base query
        $sql = "SELECT *, 
                (SELECT COUNT(*) FROM tblbooking WHERE tblbooking.PackageId = tbltourpackages.PackageId) as booking_count
                FROM tbltourpackages WHERE 1=1";
        
        $conditions = [];
        $params = [];
        
        // Apply filters
        if (!empty($filters['type'])) {
            $conditions[] = "PackageType = :type";
            $params[':type'] = $filters['type'];
        }
        
        if (!empty($filters['location'])) {
            $conditions[] = "PackageLocation LIKE :location";
            $params[':location'] = '%' . $filters['location'] . '%';
        }
        
        if (!empty($filters['price_range'])) {
            $priceRange = $filters['price_range'];
            if ($priceRange === '0-5000') {
                $conditions[] = "PackagePrice BETWEEN 0 AND 5000";
            } elseif ($priceRange === '5000-10000') {
                $conditions[] = "PackagePrice BETWEEN 5000 AND 10000";
            } elseif ($priceRange === '10000-20000') {
                $conditions[] = "PackagePrice BETWEEN 10000 AND 20000";
            } elseif ($priceRange === '20000+') {
                $conditions[] = "PackagePrice >= 20000";
            }
        }
        
        // Add conditions to query
        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }
        
        // Add ordering
        $sql .= " ORDER BY booking_count DESC, RAND() LIMIT :limit";
        
        $query = $this->dbh->prepare($sql);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $query->bindValue($key, $value);
        }
        
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get popular packages based on booking history
     * @param int $limit - Maximum number of packages
     * @return array - Popular packages
     */
    public function getPopularPackages($limit = 6) {
        $sql = "SELECT p.*, COUNT(b.BookingId) as booking_count
                FROM tbltourpackages p
                LEFT JOIN tblbooking b ON p.PackageId = b.PackageId
                GROUP BY p.PackageId
                HAVING booking_count > 0
                ORDER BY booking_count DESC, RAND()
                LIMIT :limit";
        
        $query = $this->dbh->prepare($sql);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get packages based on user preferences (if logged in)
     * @param string $userEmail - User email
     * @param int $limit - Maximum number of packages
     * @return array - Recommended packages
     */
    public function getUserPreferenceRecommendations($userEmail, $limit = 6) {
        // Get user's booking history with detailed preferences
        $sql = "SELECT DISTINCT tp.PackageType, tp.PackageLocation 
                FROM tblbooking tb
                JOIN tbltourpackages tp ON tb.PackageId = tp.PackageId
                WHERE tb.UserEmail = :email";
        
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $query->execute();
        $userPreferences = $query->fetchAll(PDO::FETCH_OBJ);
        
        if (empty($userPreferences)) {
            // No booking history, return popular packages
            return $this->getPopularPackages($limit);
        }
        
        // Build preference-based query with scoring
        $typeConditions = [];
        $locationConditions = [];
        $typeScores = [];
        $locationScores = [];
        
        foreach ($userPreferences as $pref) {
            $typeConditions[] = "PackageType LIKE '%" . $pref->PackageType . "%'";
            $locationConditions[] = "PackageLocation LIKE '%" . $pref->PackageLocation . "%'";
            
            // Build scoring arrays
            if (!isset($typeScores[$pref->PackageType])) {
                $typeScores[$pref->PackageType] = 0;
            }
            $typeScores[$pref->PackageType]++;
            
            if (!isset($locationScores[$pref->PackageLocation])) {
                $locationScores[$pref->PackageLocation] = 0;
            }
            $locationScores[$pref->PackageLocation]++;
        }
        
        // Create scoring query
        $sql = "SELECT *, 
                (SELECT COUNT(*) FROM tblbooking WHERE tblbooking.PackageId = tbltourpackages.PackageId) as booking_count,
                CASE 
                    WHEN PackageType IN ('" . implode("','", array_keys($typeScores)) . "') THEN 3
                    WHEN (" . implode(" OR ", $typeConditions) . ") THEN 2
                    ELSE 1
                END as type_score,
                CASE 
                    WHEN PackageLocation IN ('" . implode("','", array_keys($locationScores)) . "') THEN 3
                    WHEN (" . implode(" OR ", $locationConditions) . ") THEN 2
                    ELSE 1
                END as location_score
                FROM tbltourpackages 
                WHERE (" . implode(" OR ", $typeConditions) . ") 
                OR (" . implode(" OR ", $locationConditions) . ")
                ORDER BY (type_score + location_score + booking_count) DESC, RAND()
                LIMIT :limit";
        
        $query = $this->dbh->prepare($sql);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get trending packages (recently booked)
     * @param int $limit - Maximum number of packages
     * @return array - Trending packages
     */
    public function getTrendingPackages($limit = 6) {
        $sql = "SELECT p.*, COUNT(b.BookingId) as recent_bookings
                FROM tbltourpackages p
                LEFT JOIN tblbooking b ON p.PackageId = b.PackageId 
                WHERE b.RegDate >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY p.PackageId
                HAVING recent_bookings > 0
                ORDER BY recent_bookings DESC, RAND()
                LIMIT :limit";
        
        $query = $this->dbh->prepare($sql);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Get personalized recommendations for logged-in users
     * Combines multiple recommendation strategies
     */
    public function getPersonalizedRecommendations($userEmail, $limit = 6) {
        // Get user's booking history
        $sql = "SELECT DISTINCT tp.PackageId, tp.PackageType, tp.PackageLocation 
                FROM tblbooking tb
                JOIN tbltourpackages tp ON tb.PackageId = tp.PackageId
                WHERE tb.UserEmail = :email";
        
        $query = $this->dbh->prepare($sql);
        $query->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $query->execute();
        $userHistory = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($userHistory)) {
            // New user - return trending packages
            return $this->getTrendingPackages($limit);
        }
        
        // Extract user preferences
        $bookedPackageIds = array_column($userHistory, 'PackageId');
        $preferredTypes = array_unique(array_column($userHistory, 'PackageType'));
        $preferredLocations = array_unique(array_column($userHistory, 'PackageLocation'));
        
        // Build comprehensive query
        $typePlaceholders = implode(',', array_fill(0, count($preferredTypes), '?'));
        $locationPlaceholders = implode(',', array_fill(0, count($preferredLocations), '?'));
        
        $sql = "SELECT *, 
                (SELECT COUNT(*) FROM tblbooking WHERE tblbooking.PackageId = tbltourpackages.PackageId) as booking_count,
                CASE 
                    WHEN PackageType IN ('" . implode("','", $preferredTypes) . "') THEN 3
                    ELSE 1
                END as type_match,
                CASE 
                    WHEN PackageLocation IN ('" . implode("','", $preferredLocations) . "') THEN 3
                    ELSE 1
                END as location_match
                FROM tbltourpackages 
                WHERE PackageId NOT IN ('" . implode("','", $bookedPackageIds) . "')
                ORDER BY (type_match + location_match + booking_count) DESC, RAND()
                LIMIT :limit";
        
        $query = $this->dbh->prepare($sql);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
}
?>