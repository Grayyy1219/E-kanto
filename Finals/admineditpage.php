
<table class="table">
    <tr class="tr">
        <th class="th">Item ID </th>
        <th class="th">Page Item</th>
        <th class="th">Value</th>
        <th class="th">Action</th>
    </tr>
    <?php
    $result = mysqli_query($con, "SELECT * FROM page;");
    $rowIndex = 1;

    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $itemId = $row['ItemID'];
            $itemName = $row['Itemname'];
            $itemValue = $row['value'];
            if ($itemName === "Logo") {
                $itemVisual = "<img src='$logo' width='50px';>";
            } elseif ($itemName === "Company Name") {
                $itemVisual = "<span>{$itemValue}</span>";
            } elseif ($itemName === "Background Image") {
                $itemVisual = "<img src='$backgroundimg' alt='Background' width='200px';>";
            } elseif ($itemName === "Background Color") {
                $itemVisual = "<div  class='colordiv' style='background-color: $backgroundcolor;'></div>";
            } elseif ($itemName === "Text Color") {
                $itemVisual = "<div  class='colordiv' style='background-color: $color;'></div>";
            }
            echo "<tr>";
            echo "<td class='td'> $itemId </td>";
            echo "<td class='td'style='min-width: 150px';> $itemName </td>";
            echo "<td class='td'> $itemVisual </td>";
            echo "<td class='td'> <input type='button' name='submitpagetab' class='submit-btn' onclick='openModalpagetab$itemId($itemId)' value='Change'></td>";
            echo "</tr>";
            $rowIndex++;
        }
        echo "</table>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
    ?>
    <div id="Modallogo" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal2()">&times;</span>
            <h2>Upload Image</h2>
            <form id="uploadFormPagetab" action="editpage.php" method="post" enctype="multipart/form-data" onsubmit="return adminpass2()">
                <p>Logo<input type="text" name="ItemID" readonly style="border: none; font-size: 30px ">
                <p>New image:</p> <img id="previewImage2" src=""><input type="file" name="logo" id="fileInput1" onchange="fileInputChanged2()" style="display: none;">
                <input type="submit" name="logof" value="UPLOAD">
            </form>
        </div>
    </div>
    <div id="ModalCompanyName" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal2()">&times;</span>
            <h2>Change Company Name</h2>
            <form id="uploadFormPagetab" action="editpage.php" method="post" enctype="multipart/form-data" onsubmit="return adminpass2()">
                <p>New CompanyName:</p> <input type='text' name='company' id="companyName" placeholder='<?php echo "$companyname"; ?>'>
                <input type='submit' name='companyf' class="submit-btn" value='Change'>
            </form>
        </div>
    </div>
    <div id="ModalBgImg" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal2()">&times;</span>
            <h2>Upload Image</h2>
            <form id="uploadFormPagetab" action="editpage.php" method="post" enctype="multipart/form-data" onsubmit="return adminpass2()">
                <p>New Background image:</p> <img id="previewImage3" src=""><input type="file" name="bgimg" id="fileInput2" onchange="fileInputChanged3()" style="display: none;">
                <input type="submit" name="bgimgf" class="submit-btn" value="Change">
            </form>
        </div>
    </div>
    <div id="ModalBgcolor" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal2()">&times;</span>
            <h2>Change Background Color</h2>
            <form id="uploadFormPagetab" action="editpage.php" method="post" enctype="multipart/form-data" onsubmit="return adminpass2()">
                <p>New Background Color:</p> <input type='color' name='bgColor' value='<?php echo "$backgroundcolor"; ?>' style="height: 100px;">
                <input type='submit' name='bgcolorf' class="submit-btn" value='Change'>
            </form>
        </div>
    </div>
     <div id="Modalcolor" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal2()">&times;</span>
            <h2>Change Text Color</h2>
            <form id="uploadFormPagetab" action="editpage.php" method="post" enctype="multipart/form-data" onsubmit="return adminpass2()">
                <p>New Text Color:</p> <input type='color' name='Color' value='<?php echo "$color"; ?>'
                    style="height: 100px;">
                <input type='submit' name='colorf' class="submit-btn" value='Change'>
            </form>
        </div>
    </div>
   