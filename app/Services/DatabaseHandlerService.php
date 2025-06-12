<?php

namespace App\Services;

use App\Interfaces\DatabaseHandlerInterface;
use App\Traits\ChunksArray;
use Doctrine\DBAL\Connection;

class DatabaseHandlerService implements DatabaseHandlerInterface
{
    use ChunksArray;

    public function __construct(private Connection $db) {}

    public function upsert(array $data = [], array $params = []): array
    {
        $results = [
            'inserted' => 0,
            'updated' => 0,
            'failed' => 0,
            'failures' => [],
        ];

        if(empty($data)){
            return $results;
        }
        
        $this->chunkArray($data, $params['chunk'], function ($chunk) use (&$results) {
            foreach ($chunk as $row) {
                // Clean up
                unset($row['is_valid'], $row['error_msg']);
        
                try {
                    $existing = $this->db->fetchOne(
                        'SELECT id FROM customers WHERE email = ?',
                        [$row['email']]
                    );
        
                    if ($existing) {
                        $this->db->update('customers', $row, ['email' => $row['email']]);
                        $results['updated']++;
                    } else {
                        $this->db->insert('customers', $row);
                        $results['inserted']++;
                    }
                } catch (\Throwable $e) {
                    $results['failed']++;
                    $results['failures'][] = [
                        'row' => $row,
                        'message' => $e->getMessage(),
                    ];
                }
            }
        });

        return $results;
    }

    public function all(array $cols = ['first_name', 'last_name', 'email', 'country']): array
    {
        $columns = implode(', ', $cols);
        return $this->db->fetchAllAssociative('SELECT ' . $columns . ' FROM customers');
    }

    public function find(int $id, array $cols = ['first_name', 'last_name', 'email', 'username', 'gender', 'country', 'city', 'phone']): ?array
    {
        $columns = implode(', ', $cols);
        return $this->db->fetchAssociative('SELECT ' . $columns . ' FROM customers WHERE id = ?', [$id]);
    }
}
