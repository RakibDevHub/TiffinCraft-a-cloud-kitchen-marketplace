<?php
namespace App\Models;

use DateTime;
use Exception;

class Review
{
    public static function getAllReviews($conn)
    {
        $query = "
        SELECT 
            pr.review_id,
            pr.user_id,
            pr.comments,
            pr.rating,
            pr.status,
            pr.created_at,
            u.name AS reviewer_name,
            u.profile_image AS reviewer_image
        FROM platform_reviews pr
        JOIN users u ON pr.user_id = u.user_id
        ORDER BY pr.created_at DESC
    ";
        $stmt = oci_parse($conn, $query);
        oci_execute($stmt);

        $reviews = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reviews[] = self::processData($row);
        }
        oci_free_statement($stmt);
        return $reviews;
    }

    public static function getPlatFormReviews($conn)
    {
        $query = "
        SELECT 
            pr.review_id,
            pr.comments,
            pr.rating,
            pr.created_at,
            u.name AS reviewer_name,
            u.profile_image AS reviewer_image,
            u.role
        FROM platform_reviews pr
        JOIN users u ON pr.user_id = u.user_id
        WHERE pr.status = 'active'
        ORDER BY pr.created_at DESC
        FETCH FIRST 5 ROWS ONLY
    ";

        $stmt = oci_parse($conn, $query);
        oci_execute($stmt);

        $reviews = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reviews[] = self::processData($row);
        }

        oci_free_statement($stmt);
        return $reviews;
    }

    public static function hasUserReviewed($conn, $userId)
    {
        $query = "SELECT COUNT(*) AS total FROM platform_reviews WHERE user_id = :user_id";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return ($row && intval($row['TOTAL']) > 0);
    }

    public static function submitReview($conn, $userId, $rating, $comments)
    {
        $query = "
        INSERT INTO platform_reviews (user_id, rating, comments, status, created_at)
        VALUES (:user_id, :rating, :comments, 'pending', SYSDATE)
    ";

        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':rating', $rating);
        oci_bind_by_name($stmt, ':comments', $comments);

        $result = oci_execute($stmt);
        oci_free_statement($stmt);
        return $result;
    }

    public static function updateStatus($conn, $reviewId, $status)
    {
        $query = "UPDATE platform_reviews SET status = :status WHERE review_id = :review_id";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':status', $status);
        oci_bind_by_name($stmt, ':review_id', $reviewId);
        $res = oci_execute($stmt);
        oci_free_statement($stmt);
        return $res;
    }

    private static function processData(array $row)
    {
        $review = array_change_key_case($row, CASE_LOWER);

        $review['created_at'] = self::processOracleDate($kitchen['created_at'] ?? '');

        return $review;

    }

    private static function processOracleDate(string $dateString): string
    {
        try {
            $date = DateTime::createFromFormat('d-M-y h.i.s.u A', strtoupper($dateString));
            return $date ? $date->format(DateTime::ATOM) : $dateString;
        } catch (Exception $e) {
            error_log("Date processing error: " . $e->getMessage());
            return $dateString;
        }
    }

}
?>