<!DOCTYPE html>
<html>

<?php
include("connect_db.php");

?>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="itempage.css">
</head>

<body>

    <?php
    $ItemID = $_GET["ItemID"];
    $Itemname = $_GET["Itemname"];
    $Category = $_GET["Category"];
    $ItemImage = $_GET["ItemImage"];
    $Price = $_GET["Price"];
    $Solds = $_GET["Solds"];
    $Quantity = $_GET["Quantity"];
    $shortenedTitle = (strlen($Itemname) > 40) ? substr($Itemname, 0, 40) . '...' : $Itemname;
    $escapedItemID = htmlspecialchars($ItemID, ENT_QUOTES, 'UTF-8');
    ?>
    <form id="myForm" action='' method='post' enctype='multipart/form-data'>
        <section class="slideshow-container">
            <div class="section">
                <div class="heading">
                    <p class="text-wrapper">
                        <?= $shortenedTitle ?>
                    </p>
                </div>

                <div class="div">
                    <img class="div-shopee-image" src="<?= $ItemImage ?>" />
                </div>
                <div class="div-8">
                    <div class="div-flex-auto">
                        <div class="div-qnta">
                            <p class="CCL-PH-jack">
                                <input type="hidden" name="Itemname" value="<?= $Itemname ?>">
                                <?= $shortenedTitle ?>
                            </p>
                        </div>
                        <div class="section-wrapper">
                            <div class="div-10">
                                <div class="div-flex-5">
                                    <div class="div-flex-6">
                                        <div class="div-7">
                                            <div class="text-wrapper-11">
                                                ₱
                                                <?= $Price ?>
                                            </div>
                                        </div>
                                        <div class="div-voski-margin">
                                            <div class="div-voski">
                                                <div class="element-off">25% OFF</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="div-flex-7">
                            <div class="section-margin">
                                <div class="div-11">
                                    <div class="heading-3">
                                        <div class="text-wrapper-12">Shipping</div>
                                    </div>
                                    <div class="div-zqrni">
                                        <div class="div-mhanni">
                                            <div class="img-margin">
                                                <div class="div-12"></div>
                                            </div>
                                            <div class="div-wztmvh-margin">
                                                <div class="div-hd-abee">
                                                    <div class="div-7">
                                                        <div class="text-wrapper-13">Shipping Discount</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <img class="div-iudge" src="location-pointer.png" />
                                            <div class="div-flex-margin">
                                                <div class="div-2">
                                                    <div class="div-flex-wrapper">
                                                        <div class="div-flex-8">
                                                            <div class="div-opm-margin">
                                                                <div class="div-opm">
                                                                    <div class="text-wrapper-14">Shipping To</div>
                                                                </div>
                                                            </div>
                                                            <div class="div-7">
                                                                <button class="button-7">
                                                                    <div class="span">
                                                                        <div class="text-wrapper-15">Baliuag, Bulacan
                                                                        </div>
                                                                    </div>
                                                                    <img class="SVG-margin-2"
                                                                        src="img/svg-margin-2.svg" />
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="div-qhbwbq">
                                                        <div class="div-opm-margin">
                                                            <div class="shipping-fee-wrapper">
                                                                <div class="text-wrapper-14">Shipping Fee</div>
                                                            </div>
                                                        </div>
                                                        <div class="div-7">
                                                            <div class="div-pc-drawer-id">
                                                                <div class="div-flex-9">
                                                                    <div class="text-wrapper-13">₱0</div>
                                                                    <img class="SVG-margin-3"
                                                                        src="img/svg-margin-1.svg" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="div-flex-margin-2">
                                <div class="div-flex-10">
                                    <div class="div-flex-11">
                                        <div class="div-10">
                                            <div class="heading-4">
                                                <div class="text-wrapper-12">Quantity</div>
                                            </div>
                                            <div class="div-flex-6">
                                                <div class="div-hd-ecf">
                                                    <input type='hidden' name='quantity' value='<?= $initialQuantityValue ?>'>
                                                    <?php $initialQuantityValue = 1; ?>
                                                    <div class="text-wrapper-17">
                                                        <input type='number' value='<?= $initialQuantityValue ?>'
                                                            name='quantity' id='quantityInput' min='1'
                                                            max='<?= $Quantity ?>'
                                                            onchange='updateInputQuantity(this.value)'>
                                                    </div>
                                                </div>
                                                <div class="div-7">
                                                    <div class="text-wrapper-12">
                                                        <?= $Quantity ?> pieces available
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='div-25' style="max-width: 355px;">
                            <div class='div-26' onclick="submitForm('addToCart.php')">
                                <img loading='lazy' src='shopping-cart.png' class='img-3' width="20px" />
                                <div class='div-27'><input type='submit' value='Add to Basket' style='all:unset'></div>
                            </div>
                            <div class='div-28'
                                onclick="submitForm('purchaseItems2.php?selectedItems=<?= $escapedItemID ?>&Quantity=<?= $initialQuantityValue ?>&Price=<?= $Price ?>')">
                                <img loading='lazy' src='buy.png' class='img-3' width="20px" />
                                <div class='div-29'>Buy now</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <script>
        function submitForm(action) {
            // Trigger form submission
            document.getElementById("myForm").action = action;
            document.getElementById("myForm").submit();
        }
    </script>
</body>

</html>