<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>The Book Haven</title>
    <link rel="stylesheet" href="css/stylemain.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/bookpage.css">
    <link rel="icon" href="Image/logo.ico">

    <?php
    include("connect.php");
    include("query.php");
    echo "<style>
        body {
            background-color: $backgroundcolor;
        }
        .fade-overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), $backgroundcolor);
    </style>";
    ?>
</head>

<body>
    <?php
    include("header.php");
    include("popups.php");

    ?>
    <section>
        <form id="myForm" action='' method='post' enctype='multipart/form-data'>
            <?php
            $BookID = $_GET["BookID"];
            $Title = $_GET["Title"];
            $Author = $_GET["Author"];
            $Publisher = $_GET["Publisher"];
            $Genre = $_GET["Genre"];
            $BookImage = $_GET["BookImage"];
            $Forsale = $_GET["Forsale"];
            $Price = $_GET["Price"];
            $Solds = $_GET["Solds"];
            $Quantity = $_GET["Quantity"];

            $shortenedTitle = (strlen($Title) > 40) ? substr($Title, 0, 40) . '...' : $Title;
            ?>
            <div class="div">
                <div class="div-2">
                    <div class="div-3">
                        <div class="div-4">
                            <div class="column">
                                <div class="div-5">
                                    <img loading="lazy" src="<?php echo "$BookImage"; ?>" class="img">
                                </div>
                            </div>
                            <div class="column-2">

                                <div class="div-9">
                                    <div class="div-10">
                                        <?php
                                        if ($Forsale != 1) {
                                            echo "<input type='hidden' name='BookID' value='$BookID'>";
                                            echo "<div class='div-11'>( $Solds customer Borrowed )</div>";
                                        } else {
                                            echo "<input type='hidden' name='BookID' value='$BookID'>";
                                            echo "<div class='div-11'>( $Solds customer Solds )</div>";
                                        }
                                        ?>

                                    </div>
                                    <div class="div-12">
                                        <?php echo "$shortenedTitle"; ?>
                                    </div>
                                    <?php echo "<input type='hidden' name='Title' value='$Title' >" ?>
                                    <div class="div-13">
                                        <span>
                                            <?php echo "<a style='color: rgba(38, 153, 251, 1)' href='https://www.google.com/search?tbm=bks&q=$Author' target='_blank'>$Author</a>"; ?>
                                        </span>
                                        <span class="span2">(Author)</span>
                                        <?php echo "<input type='hidden' name='Author' value='$Author' >" ?>
                                    </div>
                                    <div class="div-15">
                                        <span class="span1">Publisher:</span>
                                        <span class="span2">
                                            <?php echo "$Publisher"; ?>
                                        </span>
                                    </div>
                                    <div class="div-15">
                                        <span class="span1">Category:</span>
                                        <span class="span2">
                                            <?php echo "$Genre"; ?>
                                        </span>
                                    </div>
                                    <div class="div-15">
                                        <span class="span1">Stocks:</span>
                                        <span class="span2">
                                            <?php echo "$Quantity pcs"; ?>
                                        </span>
                                        <?php echo "<input type='hidden' name='Quantity' value='$Quantity' >" ?>
                                    </div>
                                   <?php
if ($Forsale != 0) {
    $initialQuantityValue =1; 
    $escapedBookID = htmlspecialchars($BookID, ENT_QUOTES, 'UTF-8');
    echo "
    <div class='div-18'>
        <div class='div-19'>
            <div class='div-20'>Quantity</div>
            <div class='div-21'><input type='number' value='$initialQuantityValue' name='quantity' id='quantityInput' min='1' max='$Quantity' onchange='updateInputQuantity(this.value)'></div>
        </div>
        <div class='div-22'>
            <div class='div-23'>PHP $Price</div>
        </div>
    </div>
    <div class='div-25'>
        <div class='div-26' onclick=\"submitForm('addToCart.php')\">
            <img loading='lazy' src='Image/cart.png' class='img-3' />
            <div class='div-27'><input type='submit' value='Add to Basket' style='all:unset'></div>
        </div>
        <div class='div-28' onclick=\"submitForm('purchaseItems2.php?selectedItems=$escapedBookID&Quantity=$initialQuantityValue&Price=$Price')\">
            <img loading='lazy' src='Image/save.png' class='img-3' />
            <div class='div-29'>Buy now</div>
        </div>
    </div>";
} else {
    echo "
    <div class='div-15'>
        <span class='span1'>Borrow Period:</span>
        <span class='span2'>3 days</span>
    </div>
    <div class='div-282' onclick=\"submitForm('borrow.php')\">
        <img loading='lazy' src='Image/save.png' class='img-3' />
        <input type='submit' style='all:unset' class='div-29' value='Borrow Now'>
    </div>";
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var initialQuantityValue = document.getElementById('quantityInput').value;
        updateInputQuantity(initialQuantityValue);
    });

    function updateInputQuantity(value) {
        var formattedValue = encodeURIComponent(value);
        var buyNowButton = document.querySelector('.div-28');
        buyNowButton.setAttribute('onclick', 'submitForm(\'purchaseItems2.php?selectedItems=' + '<?= $escapedBookID ?>' + '&Quantity=' + formattedValue + '&Price=' + <?= $Price ?> + '\')');
    }
</script>





                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="div-30">
                        <div class="div-30">
                            <div class="div-5">
                                <div class="div-6">Related Books</div>
                                <div class="div-7">
                                    <div class="div-8"></div>
                                </div>
                            </div>
                            <br>
                            <div class="div-31">
                                <div class="column-3">
                                    <div class="div-32">
                                        <div class="div-33">
                                            <img loading="lazy" src="upload\books\book2.jpg" class="img-5" />
                                        </div>
                                        <div class="div-34">Macmillan Graded Readers: Upper Intermediate: Macbeth</div>
                                        <div class="div-35">William Shakespeare</div>
                                        <div class="div-36">
                                            <div class="div-66">PHP 1500.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <script>
        function submitForm(action) {
            // Trigger form submission
            document.getElementById("myForm").action = action;
            document.getElementById("myForm").submit();
        }

        function validateForm() {
            var newPassword = document.getElementById('newpass').value;
            var confirmPassword = document.getElementById('confirmpass').value;
            var passwordInputs = document.querySelectorAll('.password-input');

            if (newPassword !== confirmPassword) {
                alert("New Password and Confirm Password must match!");
                passwordInputs.forEach(function (element) {
                    element.classList.add('password-mismatch');
                });
                return false; // prevent form submission
            } else {
                passwordInputs.forEach(function (element) {
                    element.classList.remove('password-mismatch');
                });
            }

            return true; // allow form submission
        }

        function closeSettingsPopup() {
            document.getElementById('SettingsPopup').style.display = 'none';
            var overlay = document.querySelector('.popup-overlay');
            overlay.style.opacity = 0;
            setTimeout(function () {
                overlay.style.display = 'none';
            }, 300);
        }

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
        document.getElementById('img').addEventListener('change', function (event) {
            const fileInput = event.target;
            const profileImage = document.getElementById('profileImage');

            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>