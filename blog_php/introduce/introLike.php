<?php
include "../connect/connect.php";
include "../connect/session.php";

if (!isset($_SESSION['memberId'])) {
    echo json_encode(['error' => '로그인이 필요합니다.']);
    exit;
}

$memberId = $_SESSION['memberId'];
$introId = $_POST['introId'] ?? '';

// 이미 좋아요 또는 싫어요를 눌렀는지 확인
$likeCheckSql = "SELECT * FROM IntroLikes WHERE memberId = ? AND introId = ? AND introLike = 1";
$likeCheckStmt = $connect->prepare($likeCheckSql);
$likeCheckStmt->bind_param("is", $memberId, $introId);
$likeCheckStmt->execute();
$likeCheckResult = $likeCheckStmt->get_result();

$dislikeCheckSql = "SELECT * FROM IntroLikes WHERE memberId = ? AND introId = ? AND introLike = 0";
$dislikeCheckStmt = $connect->prepare($dislikeCheckSql);
$dislikeCheckStmt->bind_param("is", $memberId, $introId);
$dislikeCheckStmt->execute();
$dislikeCheckResult = $dislikeCheckStmt->get_result();

// if ($likeCheckResult->num_rows > 0) {
//     echo json_encode(['error' => '이미 좋아요를 누르셨습니다.']);
//     exit;
// }

// if ($dislikeCheckResult->num_rows > 0) {
//     echo json_encode(['error' => '이미 싫어요를 누르셨습니다.']);
//     exit;
// }

// 좋아요 또는 싫어요 버튼에 따라 SQL 쿼리 선택
$likeType = $_POST['likeType'] ?? ''; // 'like' 또는 'dislike' 값으로 설정되어야 합니다

if ($likeType == 'like') {
    $updateLikesSql = "INSERT INTO IntroLikes (memberId, introId, introDislike, introLike, regTime) VALUES (?, ?, 0, 1, UNIX_TIMESTAMP())";
} elseif ($likeType == 'dislike') {
    $updateLikesSql = "INSERT INTO IntroLikes (memberId, introId, introDislike, introLike, regTime) VALUES (?, ?, 1, 0, UNIX_TIMESTAMP())";
} else {
    echo json_encode(['error' => '올바르지 않은 좋아요 유형입니다.']);
    exit;
}

// SQL 쿼리 실행
$updateLikesStmt = $connect->prepare($updateLikesSql);
$updateLikesStmt->bind_param("is", $memberId, $introId);
$updateLikesStmt->execute();

// 좋아요 또는 싫어요 수 조회 쿼리 작성
$getLikesCountSql = "SELECT COUNT(*) as likeCount FROM IntroLikes WHERE introId = ? AND introLike = 1";
$getDislikesCountSql = "SELECT COUNT(*) as dislikeCount FROM IntroLikes WHERE introId = ? AND introDislike = 1";

// 좋아요 수 조회
$likeCountStmt = $connect->prepare($getLikesCountSql);
$likeCountStmt->bind_param("s", $introId);
$likeCountStmt->execute();
$likeCountResult = $likeCountStmt->get_result();
$likeCountRow = $likeCountResult->fetch_assoc();
$likeCount = $likeCountRow['likeCount'];

// 싫어요 수 조회
$dislikeCountStmt = $connect->prepare($getDislikesCountSql);
$dislikeCountStmt->bind_param("s", $introId);
$dislikeCountStmt->execute();
$dislikeCountResult = $dislikeCountStmt->get_result();
$dislikeCountRow = $dislikeCountResult->fetch_assoc();
$dislikeCount = $dislikeCountRow['dislikeCount'];

// 결과 반환 (JSON 형식)
$response = array(
    'likeCount' => $likeCount,
    'dislikeCount' => $dislikeCount
);

echo json_encode($response);

?>