<?php
require_once '../classes/ApiService.php';
    class Controller {
        protected $apiService;

        public function __construct() {
            $this->apiService = new ApiService();
        }

        public function getWeather($latitude, $longtitude) {
            try {
                $weatherData = $this->apiService->getData($latitude, $longtitude);
                return $weatherData;
            } catch (Exception $e) {
                return ["error" => $e->getMessage()];
            }
        }
    }
?>