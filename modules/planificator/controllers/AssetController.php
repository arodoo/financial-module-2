<?php
require_once __DIR__ . '/../models/Asset.php';
require_once __DIR__ . '/../models/Membre.php';

class AssetController {
    private $assetModel;
    private $currentUser;

    public function __construct() {
        global $id_oo;
        $this->assetModel = new Asset();
        $this->currentUser = ['id' => $id_oo];
    }

    /**
     * Get all asset categories
     */
    public function getCategories() {
        return $this->assetModel->getCategories();
    }

    /**
     * Get all assets for current user
     */
    public function getAssets() {
        if (!$this->currentUser['id']) return [];
        
        return $this->assetModel->getAssetsByMemberId($this->currentUser['id']);
    }

    /**
     * Get asset by ID
     */
    public function getAssetById($id) {
        if (!$this->currentUser['id']) return null;
        
        return $this->assetModel->getAssetById($id);
    }

    /**
     * Save new asset
     */
    public function saveAsset($data) {
        if (!$this->currentUser['id']) return false;
        
        // Debug log input data
        error_log("AssetController saveAsset input data: " . print_r($data, true));
        
        // Handle both field name formats
        $name = isset($data['name']) ? $data['name'] : (isset($data['asset_name']) ? $data['asset_name'] : null);
        
        if (empty($name)) {
            error_log("Asset name is missing");
            return false;
        }
        
        $assetData = [
            'membre_id' => $this->currentUser['id'],
            'name' => $name,
            'category_id' => (int)$data['category_id'],
            // Map form fields to database columns (purchase_date, purchase_value)
            'purchase_date' => !empty($data['acquisition_date']) ? $data['acquisition_date'] : date('Y-m-d'),
            'purchase_value' => floatval(str_replace([' ', ','], ['', '.'], $data['acquisition_value'])),
            'last_valuation_date' => !empty($data['valuation_date']) ? $data['valuation_date'] : date('Y-m-d'),
            'current_value' => floatval(str_replace([' ', ','], ['', '.'], $data['current_value'])),
            'location' => $data['location'] ?? null,
            'notes' => $data['notes'] ?? null
        ];
        
        // Debug log processed data
        error_log("AssetController saveAsset processed data: " . print_r($assetData, true));
        
        try {
            $result = $this->assetModel->saveAsset($assetData);
            error_log("Asset save result: " . ($result ? "Success with ID: $result" : "Failed"));
            return $result;
        } catch (Exception $e) {
            error_log("Exception in AssetController::saveAsset: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update existing asset
     */
    public function updateAsset($data) {
        if (!$this->currentUser['id']) return false;
        
        $assetId = (int)$data['asset_id'];
        
        // Handle both field name formats (for backward compatibility)
        $name = isset($data['name']) ? $data['name'] : (isset($data['asset_name']) ? $data['asset_name'] : null);
        
        if (empty($name)) {
            error_log("Asset name is missing in update");
            return false;
        }
        
        $assetData = [
            'membre_id' => $this->currentUser['id'],
            'name' => $name,
            'category_id' => (int)$data['category_id'],
            // Map form fields to database columns
            'purchase_date' => !empty($data['acquisition_date']) ? $data['acquisition_date'] : date('Y-m-d'),
            'purchase_value' => floatval(str_replace([' ', ','], ['', '.'], $data['acquisition_value'])),
            'last_valuation_date' => !empty($data['valuation_date']) ? $data['valuation_date'] : date('Y-m-d'),
            'current_value' => floatval(str_replace([' ', ','], ['', '.'], $data['current_value'])),
            'location' => $data['location'] ?? null,
            'notes' => $data['notes'] ?? null
        ];
        
        try {
            return $this->assetModel->updateAsset($assetId, $assetData);
        } catch (Exception $e) {
            error_log("Exception in AssetController::updateAsset: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an asset
     */
    public function deleteAsset($assetId) {
        if (!$this->currentUser['id']) return false;
        
        return $this->assetModel->deleteAsset((int)$assetId);
    }

    /**
     * Get data for the view
     */
    public function getViewData() {
        $data = [];
        
        // Get all asset categories
        $data['categories'] = $this->getCategories();
        
        // Get all assets for the current user
        $data['assets'] = $this->getAssets();
        
        // Check if a specific asset is requested to view
        if (isset($_GET['view_asset'])) {
            $assetId = (int)$_GET['view_asset'];
            $asset = $this->getAssetById($assetId);
            
            if ($asset) {
                $data['viewAsset'] = $asset;
            }
        }
        
        // Check if a specific asset is requested to edit
        if (isset($_GET['edit_asset'])) {
            $assetId = (int)$_GET['edit_asset'];
            $asset = $this->getAssetById($assetId);
            
            if ($asset) {
                $data['editAsset'] = $asset;
            }
        }
        
        return $data;
    }
}
?>
