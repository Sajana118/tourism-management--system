<?php
/**
 * TOURISM MANAGEMENT SYSTEM - SEASONAL PRICING

 * Seasonal Pricing Algorithm (Dynamic pricing based on seasons and demand)
 */

class SeasonalPricing {
    // Peak seasons by location (months 1-12)
    private $peakSeasons = [
        'Kathmandu' => [3, 4, 5, 10, 11], // Spring and Autumn
        'Pokhara' => [3, 4, 5, 10, 11],
        'Everest' => [3, 4, 5, 9, 10, 11], // Spring and Autumn (best for trekking)
        'Chitwan' => [10, 11, 12, 1, 2, 3], // Winter and Spring (dry season)
        'Annapurna' => [3, 4, 5, 9, 10, 11],
        'Lumbini' => [10, 11, 12, 1, 2, 3],
        'Nagarkot' => [3, 4, 5, 10, 11],
        'Mustang' => [9, 10, 11, 3, 4, 5], // Spring and Autumn
        'Langtang' => [3, 4, 5, 9, 10, 11],
        'Rara' => [9, 10, 11, 3, 4, 5],
        'Gosaikunda' => [7, 8, 9], // Monsoon (pilgrimage season)
        'Tilicho' => [9, 10, 11]
    ];
    
    /**
     * Calculate seasonal price multiplier for a package
     * @param string $packageLocation The location of the package
     * @param string $bookingDate The date of booking (Y-m-d format)
     * @return float Price multiplier (e.g., 1.2 = 20% increase, 0.9 = 10% decrease)
     */
    public function getSeasonalMultiplier($packageLocation, $bookingDate) {
        // Handle null or empty package location
        if (empty($packageLocation)) {
            return 1.0; // Default price
        }
        
        $month = (int)date('n', strtotime($bookingDate));
        
        // Find the location in our peak seasons array
        $locationKey = '';
        foreach (array_keys($this->peakSeasons) as $loc) {
            if (stripos($packageLocation, $loc) !== false) {
                $locationKey = $loc;
                break;
            }
        }
        
        // If location not found, return default multiplier
        if (empty($locationKey) || !isset($this->peakSeasons[$locationKey])) {
            return 1.0; // Default price
        }
        
        // Check if current month is in peak season for this location
        if (in_array($month, $this->peakSeasons[$locationKey])) {
            return 1.2; // 20% higher price during peak season
        } else {
            return 0.9; // 10% lower price during off-season
        }
    }
    
    /**
     * Calculate final price with seasonal adjustment
     * @param float $basePrice The original package price
     * @param string $packageLocation The location of the package
     * @param string $bookingDate The date of booking (Y-m-d format)
     * @return float The adjusted price
     */
    public function calculateSeasonalPrice($basePrice, $packageLocation, $bookingDate) {
        $multiplier = $this->getSeasonalMultiplier($packageLocation, $bookingDate);
        return round($basePrice * $multiplier, 2);
    }
    
    /**
     * Get seasonal pricing information for display
     * @param string $packageLocation The location of the package
     * @param string $bookingDate The date of booking (Y-m-d format)
     * @return array Information about seasonal pricing
     */
    public function getSeasonalInfo($packageLocation, $bookingDate) {
        // Handle null or empty package location
        if (empty($packageLocation)) {
            return [
                'isPeakSeason' => false,
                'multiplier' => 1.0,
                'message' => 'Standard pricing'
            ];
        }
        
        $multiplier = $this->getSeasonalMultiplier($packageLocation, $bookingDate);
        $month = (int)date('n', strtotime($bookingDate));
        
        $locationKey = '';
        foreach (array_keys($this->peakSeasons) as $loc) {
            if (stripos($packageLocation, $loc) !== false) {
                $locationKey = $loc;
                break;
            }
        }
        
        if (empty($locationKey) || !isset($this->peakSeasons[$locationKey])) {
            return [
                'isPeakSeason' => false,
                'multiplier' => 1.0,
                'message' => 'Standard pricing'
            ];
        }
        
        $isPeak = in_array($month, $this->peakSeasons[$locationKey]);
        
        return [
            'isPeakSeason' => $isPeak,
            'multiplier' => $multiplier,
            'message' => $isPeak ? 'Peak season pricing (20% increase)' : 'Off-season pricing (10% discount)'
        ];
    }
}

?>