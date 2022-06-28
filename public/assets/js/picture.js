'use strict';

/***********************************************************************************/
/* *********************************** DONNEES *************************************/
/***********************************************************************************/

const activeImgElt = document.getElementById("activimg");
const inputFileButton = document.getElementById("inputFileButton"); 
const inputFile = document.getElementById("inputFile"); 

const imgNameSelectedElt = document.getElementById("imgNameSelected");
const customSpace = document.getElementById("customSpace");



// /***********************************************************************************/
/* ********************************** FONCTIONS ************************************/
/***********************************************************************************/




/************************************************************************************/
/* ******************************** CODE PRINCIPAL **********************************/
/************************************************************************************/
document.addEventListener('DOMContentLoaded', () => {
    inputFileButton.addEventListener("click", function () {
        inputFile.click();
    });
    
    inputFile.addEventListener("change", function () {
        if (inputFile.value) {
            const fileName = inputFile.value.match(/[\/\\]([\w\d\s\-\.\(\)]+)$/)[1];
            customSpace.value = fileName.split(".")[0];
        } else {
            customSpace.value = "";
        }
        const files = inputFile.files[0];
        if (files) {
            const fileReader = new FileReader();
            fileReader.addEventListener("load", function () {
                activeImgElt.setAttribute("src", this.result);
            });
            fileReader.readAsDataURL(files);
        }
    });
    
    imgNameSelectedElt.addEventListener("change", function () {
        let selectedItem = imgNameSelectedElt.options[imgNameSelectedElt.selectedIndex];
        customSpace.value = selectedItem.text;
        activeImgElt.setAttribute("src", selectedItem.value);
    });
});    


