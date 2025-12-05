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

        foreach ($filters as $key => $value) {
            // On suppose que les clés du tableau correspondent aux colonnes de la table
            $where[] = "$key = :$key";
            $params[$key] = $value;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $result = $this->db->query($sql, $params);
        $products = [];

        while ($row = $result->fetch()) {
            $products[] = new Product($row);
        }

        return $products;
    }
}
