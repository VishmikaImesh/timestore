<?php

require_once(BASE . "/config/connection.php");

class search
{
    private $validGenders = [1, 2]; // 1=male, 2=female
    private $validMaterialTypes = ['steel', 'leather', 'gold', 'platinum', 'ceramic', 'titanium'];
    private $validTypes = ['analog', 'digital', 'smart', 'automatic', 'quartz'];
    private $maxSearchLength = 100;
    private $maxResultsPerPage = 50;

    public function search($data)
    {
        try {
            // Validate and sanitize all inputs
            $validation = $this->validateInputs($data);
            if (!$validation['valid']) {
                echo json_encode(["state" => false, "data" => [], "message" => $validation['message']]);
                return;
            }

        $g = $validation['gender'];
        $mt = $validation['material_type'];
        $type = $validation['type'];
        $search = $validation['search'];
        $page = $validation['page'];
        $limit = $validation['limit'];

        // Build query with validated parameters
        $query = $this->buildSearchQuery($g, $mt, $type, $search, $page, $limit);
        
        $search_rs = Database::search($query);
        if (!$search_rs) {
            echo json_encode(["state" => false, "data" => [], "message" => "Search query failed"]);
            return;
        }

        $search_no = $search_rs->num_rows;
        $items = [];

        for ($i = 0; $i < $search_no; $i++) {
            $search_data = $search_rs->fetch_assoc();
            $img_rs = Database::search("SELECT * FROM `product_img` WHERE `product_id`='" . $search_data["id"] . "' LIMIT 1");
            $img_data = $img_rs ? $img_rs->fetch_assoc() : null;

            $img_path = $img_data ? htmlspecialchars($img_data["img_path"], ENT_QUOTES, 'UTF-8') : "";
            $product_id = intval($search_data["id"]);
            $title = htmlspecialchars($search_data["title"], ENT_QUOTES, 'UTF-8');
            $price = htmlspecialchars($search_data["price"], ENT_QUOTES, 'UTF-8');

            $items[] = [
                "product_id" => $product_id,
                "title" => $title,
                "price" => $price,
                "img_path" => $img_path
            ];
        }

            echo json_encode(["state" => true, "data" => $items, "count" => count($items), "page" => $page, "limit" => $limit]);
        } catch (Exception $e) {
            echo json_encode(["state" => false, "data" => [], "message" => "Search error: " . $e->getMessage()]);
        }
    }

    private function validateInputs($data)
    {
        // Validate gender (required)
        if (!isset($data['g']) || $data['g'] === '' || $data['g'] === null) {
            return ['valid' => false, 'message' => 'Gender filter is required'];
        }

        $gender = filter_var($data['g'], FILTER_VALIDATE_INT);
        if ($gender === false || !in_array($gender, $this->validGenders)) {
            return ['valid' => false, 'message' => 'Invalid gender value. Must be 1 (male) or 2 (female)'];
        }

        // Validate material type (optional)
        $materialType = null;
        if (isset($data['mt']) && !empty($data['mt'])) {
            $mt = strtolower(trim($data['mt']));
            if (!in_array($mt, $this->validMaterialTypes)) {
                return ['valid' => false, 'message' => 'Invalid material type. Allowed: ' . implode(', ', $this->validMaterialTypes)];
            }
            $materialType = $mt;
        }

        // Validate product type (optional)
        $productType = null;
        if (isset($data['type']) && !empty($data['type'])) {
            $type = strtolower(trim($data['type']));
            if (!in_array($type, $this->validTypes)) {
                return ['valid' => false, 'message' => 'Invalid product type. Allowed: ' . implode(', ', $this->validTypes)];
            }
            $productType = $type;
        }

        // Validate search text (optional)
        $searchText = null;
        if (isset($data['search']) && !empty($data['search'])) {
            $search = trim($data['search']);
            if (strlen($search) > $this->maxSearchLength) {
                return ['valid' => false, 'message' => 'Search text too long. Maximum ' . $this->maxSearchLength . ' characters'];
            }
            // Remove potentially dangerous characters
            $search = preg_replace('/[^\w\s\-]/', '', $search);
            if (strlen($search) < 2) {
                return ['valid' => false, 'message' => 'Search text must be at least 2 characters'];
            }
            $searchText = $search;
        }

        // Validate pagination parameters
        $page = 1;
        if (isset($data['page']) && !empty($data['page'])) {
            $page = filter_var($data['page'], FILTER_VALIDATE_INT);
            if ($page === false || $page < 1) {
                return ['valid' => false, 'message' => 'Invalid page number. Must be a positive integer'];
            }
            if ($page > 1000) {
                return ['valid' => false, 'message' => 'Page number too high. Maximum 1000'];
            }
        }

        $limit = 20; // default
        if (isset($data['limit']) && !empty($data['limit'])) {
            $limit = filter_var($data['limit'], FILTER_VALIDATE_INT);
            if ($limit === false || $limit < 1) {
                return ['valid' => false, 'message' => 'Invalid limit. Must be a positive integer'];
            }
            if ($limit > $this->maxResultsPerPage) {
                return ['valid' => false, 'message' => 'Limit too high. Maximum ' . $this->maxResultsPerPage . ' results per page'];
            }
        }

        return [
            'valid' => true,
            'gender' => $gender,
            'material_type' => $materialType,
            'type' => $productType,
            'search' => $searchText,
            'page' => $page,
            'limit' => $limit
        ];
    }

    private function buildSearchQuery($gender, $materialType, $productType, $search, $page, $limit)
    {
        $conditions = [];
        $conditions[] = "`gender_id`='" . Database::escape($gender) . "'";

        // Add material type filter if provided
        if ($materialType !== null) {
            $conditions[] = "`material_type` LIKE '%" . Database::escape($materialType) . "%'";
        }

        // Add product type filter if provided
        if ($productType !== null) {
            $conditions[] = "`type` LIKE '%" . Database::escape($productType) . "%'";
        }

        // Add search text filter if provided
        if ($search !== null) {
            $escapedSearch = Database::escape($search);
            $conditions[] = "(`title` LIKE '%" . $escapedSearch . "%' OR `description` LIKE '%" . $escapedSearch . "%')";
        }

        $whereClause = implode(' AND ', $conditions);
        $offset = ($page - 1) * $limit;

        return "SELECT * FROM `product` WHERE " . $whereClause . " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    }
}
