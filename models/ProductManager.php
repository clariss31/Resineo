<?php

class ProductManager extends AbstractEntityManager
{
    /**
     * Récupère tous les produits.
     * @return Product[]
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM products";
        $result = $this->db->query($sql);
        $products = [];

        while ($row = $result->fetch()) {
            $products[] = new Product($row);
        }

        return $products;
    }

    /**
     * Récupère un produit par son ID.
     * @param int $id
     * @return Product|null
     */
    public function findOneById(int $id): ?Product
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $row = $result->fetch();

        if ($row) {
            return new Product($row);
        }

        return null;
    }

    /**
     * Récupère les produits selon des filtres.
     * @param array $filters Tableau associatif colonne => valeur
     * @return Product[]
     */
    public function findByFilter(array $filters): array
    {
        $sql = "SELECT * FROM products";
        $params = [];
        $where = [];

        // Filtre par catégorie (int ou array)
        if (!empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                $ids = array_map('intval', $filters['category_id']);
                if (!empty($ids)) {
                    $where[] = "category_id IN (" . implode(',', $ids) . ")";
                }
            } else {
                $where[] = "category_id = :category_id";
                $params['category_id'] = $filters['category_id'];
            }
        }

        // Filtre par prix minimum
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $where[] = "price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        // Filtre par prix maximum
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $where[] = "price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        // Autres filtres génériques (color, scent...)
        $genericFilters = ['color', 'scent', 'tool_type'];
        foreach ($genericFilters as $key) {
            if (!empty($filters[$key])) {
                $where[] = "$key = :$key";
                $params[$key] = $filters[$key];
            }
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        // Tri
        if (!empty($filters['order_by'])) {
            $direction = (isset($filters['direction']) && strtoupper($filters['direction']) === 'DESC') ? 'DESC' : 'ASC';
            $allowedSorts = ['price', 'name', 'id']; // 'id' sert de proxy pour la date si 'created_at' n'existe pas

            if (in_array($filters['order_by'], $allowedSorts)) {
                $sql .= " ORDER BY " . $filters['order_by'] . " " . $direction;
            }
        }

        $result = $this->db->query($sql, $params);
        $products = [];

        while ($row = $result->fetch()) {
            $products[] = new Product($row);
        }

        return $products;
    }

    /**
     * Récupère les produits par catégorie.
     * @param int $categoryId
     * @return Product[]
     */
    public function findByCategory(int $categoryId): array
    {
        return $this->findByFilter(['category_id' => $categoryId]);
    }

    /**
     * Récupère les valeurs distinctes pour une colonne donnée.
     * @param string $column
     * @return array
     */
    public function getDistinctValues(string $column): array
    {
        // Whitelist des colonnes autorisées pour éviter les injections SQL
        $allowedColumns = ['color', 'scent', 'tool_type', 'category_id'];
        if (!in_array($column, $allowedColumns)) {
            return [];
        }

        $sql = "SELECT DISTINCT $column FROM products WHERE $column IS NOT NULL AND $column != ''";
        $result = $this->db->query($sql);
        $values = [];

        while ($row = $result->fetch()) {
            $values[] = $row[$column];
        }

        return $values;
    }

    /**
     * Récupère le prix minimum et maximum des produits (filtrés).
     * @param array $filters
     * @return array ['min_price' => float, 'max_price' => float]
     */
    public function getMinMaxPrices(array $filters = []): array
    {
        $sql = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM products";
        $params = [];
        $where = [];

        // Réutilisation de la logique de filtrage (sauf prix)
        if (!empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                $ids = array_map('intval', $filters['category_id']);
                if (!empty($ids)) {
                    $where[] = "category_id IN (" . implode(',', $ids) . ")";
                }
            } else {
                $where[] = "category_id = :category_id";
                $params['category_id'] = $filters['category_id'];
            }
        }

        // ... autres filtres si nécessaire

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $result = $this->db->query($sql, $params);
        $row = $result->fetch();

        return [
            'min_price' => $row['min_price'] ?? 0,
            'max_price' => $row['max_price'] ?? 1000
        ];
    }
}
