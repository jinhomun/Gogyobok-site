<?php
    include "../connect/connect.php";
    include "../connect/session.php";


?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go!교복</title>
    
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/mypage.css">
    <style>
        .mypage__inner {
            padding: 5rem 0;
            min-height: 90vh;
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
        <section class="join__inner join__join mypage__inner container">
            <h2>비밀번호 변경</h2>
            <p>😎 현재 사용중인 비밀번호를 입력한 후 바꾸고 싶은 비밀번호를 입력해주세요.</p>
            <div class="join__form join__form__cont">
                <form action="PassModify.php" name="PassModify" method="post" onsubmit="return joinChecks();">
                    <div class="check_input">
                        <label for="currentPassword" class="required">현재 비밀번호</label>
                        <input type="password" id="currentPassword" name="currentPassword" placeholder="비밀번호를 적어주세요!" class="input__style">  
                        <p class="msg" id="currentPasswordComment"></p>
                    </div>
                    
                    <div class="check_input">
                        <label for="newPassword" class="required">변경할 비밀번호</label>
                        <input type="password" id="newPassword" name="newPassword" placeholder="변경할 비밀번호를 적어주세요!" class="input__style">  
                        <p class="msg" id="newPasswordComment"></p>
                    </div>
                    <!-- <div class="check_input">
                        <label for="youPassC" class="required">비밀번호 확인</label>
                        <input type="password" id="youPassC" name="youPassC" placeholder="다시 한번 비밀번호를 적어주세요!" class="input__style">
                        <p class="msg" id="youPassCComment"></p>
                    </div> -->

                    <button type="submit" class="btn__style mt100 join_result_btn" style="color: #fff;">비밀번호 변경하기</button>
                </form>
            </div>
        </section>
    </main>
    <!-- //main -->

    <?php include "../include/footer.php" ?>
    <!-- //footer -->
</body>
</html>