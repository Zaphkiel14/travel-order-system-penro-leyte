<?php

use App\Models\SelectModel;

if (!function_exists('pending_count')) {

    function pending_count(): int
    {
        $session = session();

        if (!$session->has('user_id') || !$session->has('role')) {
            return 0;
        }

        $userId = $session->get('user_id');
        $role   = $session->get('role');

        $cache = cache();
        $key = "pending_summary_{$userId}_{$role}";

        $cached = $cache->get($key);
        if ($cached) {
            return $cached['pending_count'] ?? 0;
        }

        $model = new SelectModel();
        $data = $model->getUserPendingSummary($userId, $role);

        $count = $data['pending_count'] ?? 0;

        $cache->save($key, ['pending_count' => $count], 10);

        return $count;
    }
}