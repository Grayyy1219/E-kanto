<div class="popup-overlay"></div>
<div id="spopup-overlay"></div>
<div id="SettingsPopup" class="spopup">
    <div class="popup-content">
        <span class="close" onclick="closeSettingsPopup()"><b>&times;</b></span>
        <div class="sdiv">
            <form action="" class="settings" method="post" enctype="multipart/form-data">
                <?php
                echo "<div class='profileimg'><p><img  src='$alocation' width='200 height='200'></p><br><br>";
                echo "<p class='name'><b>" . $aFName . " " . $aLName . "</b></p>";
                echo "<p class='emaillink'>" . $aemail . "</p></div>";
                ?>
                <div class="ssbuttons">
                    <a href="aedituser.php">
                        <div class="inbtn">
                            <img src="Image/resume.png" width="25">
                            <p>Edit Basic Information</p>
                        </div>
                    </a>
                    <a href="aeditpass.php">
                        <div class="inbtn">
                            <img src="Image/security.png" width="25">
                            <p>Change Password</p>
                        </div>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function showSettingsPopup() {
        document.getElementById("SettingsPopup").style.display = "block";
        setTimeout(function () {
            document.getElementById("spopup-overlay").style.display = "block";
        }, 10);
    }

    function closeSettingsPopup() {
        document.getElementById("spopup-overlay").style.display = "none";
        document.getElementById("SettingsPopup").style.display = "none";
    }
</script>