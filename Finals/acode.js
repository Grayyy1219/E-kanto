


function openModal(index) {
    document.getElementsByName('slideindex')[0].value = index;
    document.getElementById('fileInput').click();
    document.getElementById("myModal").style.display = "block";
}

function closeModal() {
    var modal = document.getElementById("myModal");
    var previewImage = document.getElementById('previewImage');
    var slideIndexInput = document.getElementsByName('slideindex')[0];

    previewImage.src = "";
    slideIndexInput.value = "";

    if (modal.style.display == "block") {
        modal.style.display = "none";
    }
}

function fileInputChanged() {
    var index = document.getElementsByName('slideindex')[0].value;
    openModal(index);
    var fileInput = document.getElementById('fileInput');
    var previewImage = document.getElementById('previewImage');

    if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            previewImage.src = e.target.result;
        };

        reader.readAsDataURL(fileInput.files[0]);
    }
}

function openModalPageTab(index, fileInputId, modalId) {
    document.getElementsByName('ItemID')[0].value = index;
    document.getElementById(fileInputId).click();
    document.getElementById(modalId).style.display = "block";
}


function closeModal2() {
    var modal2 = document.getElementById("Modallogo");
    var modal3 = document.getElementById("ModalCompanyName");
    var modal4 = document.getElementById("ModalBgImg");
    var modal5 = document.getElementById("ModalBgcolor");

    closeModalIfOpen(modal2);
    closeModalIfOpen(modal3);
    closeModalIfOpen(modal4);
    closeModalIfOpen(modal5);
}

function closeModalIfOpen(modal) {
    if (modal.style.display == "block") {
        modal.style.display = "none";
    }
}

function handleFileInputChange(index, fileInputId, openModalFunction, previewImageId) {
    openModalFunction(index);
    var fileInput = document.getElementById(fileInputId);
    var previewImage = document.getElementById(previewImageId);

    if (fileInput.files && fileInput.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            previewImage.src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
}

function fileInputChanged2() {
    var index = document.getElementsByName('ItemID')[0].value;
    handleFileInputChange(index, 'fileInput1', openModalPageTab1, 'previewImage2');
}

function fileInputChanged3() {
    var index = document.getElementsByName('ItemID')[0].value;
    handleFileInputChange(index, 'fileInput2', openModalPageTab3, 'previewImage3');
}
function openModalpagetab1(index) {
    document.getElementsByName('ItemID')[0].value = index;
    document.getElementById('fileInput1').click();
    document.getElementById("Modallogo").style.display = "block";
}

function openModalpagetab2(index) {
    document.getElementsByName('ItemID')[0].value = index;
    document.getElementById("ModalCompanyName").style.display = "block";
}

function openModalpagetab3(index) {
    document.getElementsByName('ItemID')[0].value = index;
    document.getElementById('fileInput2').click();
    document.getElementById("ModalBgImg").style.display = "block";
}

function openModalpagetab4(index) {
    document.getElementsByName('ItemID')[0].value = index;
    document.getElementById("ModalBgcolor").style.display = "block";
}
function openModalpagetab5(index) {
    document.getElementsByName('ItemID')[0].value = index;
    document.getElementById("Modalcolor").style.display = "block";
}

function closeModal2() {
    var modal2 = document.getElementById("Modallogo");
    if (modal2.style.display == "block") {
        modal2.style.display = "none";
    }
    var modal3 = document.getElementById("ModalCompanyName");
    if (modal3.style.display == "block") {
        modal3.style.display = "none";
    }
    var modal4 = document.getElementById("ModalBgImg");
    if (modal4.style.display == "block") {
        modal4.style.display = "none";
    }
    var modal5 = document.getElementById("ModalBgcolor");
    if (modal5.style.display == "block") {
        modal5.style.display = "none";
    }
    var modal6 = document.getElementById("Modalcolor");
    if (modal6.style.display == "block") {
        modal6.style.display = "none";
    }
}

function fileInputChanged2() {
    var index = document.getElementsByName('ItemID')[0].value;
    openModalpagetab1(index);
    var fileInput1 = document.getElementById('fileInput1');
    var previewImage2 = document.getElementById('previewImage2');

    if (fileInput1.files && fileInput1.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            previewImage2.src = e.target.result;
        };

        reader.readAsDataURL(fileInput1.files[0]);
    }
}

function fileInputChanged3() {
    var index = document.getElementsByName('ItemID')[0].value;
    openModalpagetab3(index);
    var fileInput2 = document.getElementById('fileInput2');
    var previewImage3 = document.getElementById('previewImage3');

    if (fileInput2.files && fileInput2.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            previewImage3.src = e.target.result;
        };

        reader.readAsDataURL(fileInput2.files[0]);
    }
}
function showSettingsPopup() {
    document.getElementById("SettingsPopup").style.display = "block";
    setTimeout(function () {
        document.getElementById("spopup-overlay").style.display = "block";
    }, 10); // Adjust the delay (in milliseconds) as needed
}
