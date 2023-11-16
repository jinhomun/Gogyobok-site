<?php
include "../connect/connect.php";
include "../connect/session.php";

$youName = $_SESSION['youName'];
$blogSql = "SELECT count(blogID) FROM blogs";
$blogInfo = $connect->query($blogSql);
$blogTotalCount = $blogInfo->fetch_assoc();
$blogTotalCount = $blogTotalCount['count(blogID)'];
$blogSql = "SELECT * FROM blogs WHERE blogDelete = 1 ORDER BY blogId DESC";
$blogInfo = $connect->query($blogSql);

// $viewNum 변수를 $blogTotalCount로 변경
$viewNum = $blogTotalCount;

// 현재 페이지 설정
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$startFrom = ($page - 1) * $viewNum; // 현재 페이지의 시작 위치

// 게시물 조회 쿼리를 변경하여 현재 페이지에서 10개의 게시물만 가져오도록 합니다.
$blogSql = "SELECT * FROM blogs WHERE blogDelete = 1 ORDER BY blogId DESC LIMIT $startFrom, $viewNum";
$blogInfo = $connect->query($blogSql);

if (isset($_SESSION['youName'])) {
    $youName = $_SESSION['youName'];
    // 나의 글 수 조회
    $myBlogCountSql = "SELECT count(blogID) FROM blogs WHERE blogAuthor = ?";
    $stmtCount = $connect->prepare($myBlogCountSql);
    $stmtCount->bind_param("s", $youName);
    $stmtCount->execute();
    $blogCountInfo = $stmtCount->get_result();
    $blogTotalCount = $blogCountInfo->fetch_assoc();
    $blogTotalCount = $blogTotalCount['count(blogID)'];

    // 나의 글 가져오기
    $myBlogSql = "SELECT * FROM blogs WHERE blogAuthor = ? AND blogDelete = 1 ORDER BY blogId DESC LIMIT $startFrom, $viewNum";
    $stmtBlog = $connect->prepare($myBlogSql);
    $stmtBlog->bind_param("s", $youName);
    $stmtBlog->execute();
    $blogInfo = $stmtBlog->get_result();
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go!교복</title>

    <link rel="stylesheet" href="../assets/css/cummunity.css">
    <link rel="stylesheet" href="../assets/css/mypage.css">
    <style>
        .mypage__inner {
            padding: 5rem 0;
            min-height: 90vh;
        }

        .mypage__inner h2 {
            font-size: 2.3rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .mypage__inner>p {
            font-size: 1.2rem;
            text-align: center;
            color: #555555;
            word-break: keep-all;
            font-weight: 100;
        }
    </style>
    <!-- CSS -->
    <?php include "../include/head.php" ?>

</head>

<body>
    <?php include "../include/skip.php" ?>
    <!-- //skip -->

    <?php include "../include/header.php" ?>

    <main id="main">
        <?php include "../mypage/mypageAside.php" ?>
        <section class="board__inner mypage__inner container">
            <h2>내가 쓴 글</h2>
            <p>🤗 수다방에서 내가 쓴 게시글을 볼 수 있습니다.</p>
            <div class="board__search">
                <div class="left">
                    * 총 <em>
                        <?= $blogTotalCount ?>
                    </em>건의 게시물을 등록하셨습니다!
                </div>
                <div class="right board__select">

                </div>
            </div>
            <div class="board__table">
                <table>
                    <colgroup>
                        <col style="width: 5%;">
                        <col>
                        <col style="width: 10%;">
                        <col style="width: 15%;">
                        <col style="width: 7%;">
                        <col style="width: 7%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>View</th>
                            <th>Like</th>
                        </tr>
                    </thead>
                    <tbody>




                        <?php
                        $displayBlogId = $blogTotalCount;

                        foreach ($blogInfo as $blog) {
                            if ($blog['blogAuthor'] === $youName) { ?>
                                <tr>
                                    <td>
                                        <?= $displayBlogId ?>
                                    </td>
                                    <td><a href="../cummunity/communityView.php?blogId=<?= $blog['blogId'] ?>">
                                            <?= $blog['blogTitle'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= $blog['blogAuthor'] ?>
                                    </td>
                                    <td>
                                        <?= date('Y-m-d', $blog['blogRegTime']) ?>
                                    </td>
                                    <td>
                                        <?= $blog['blogView'] ?>
                                    </td>
                                    <td>
                                        <?= $blog['blogLike'] ?>
                                    </td>
                                </tr>
                                <?php
                                $displayBlogId--;
                            }
                        } ?>




                    </tbody>
                </table>
            </div>






        </section>
    </main>
    <!-- //main -->

    <?php include "../include/footer.php" ?>
    <!-- //footer -->
</body>

</html>