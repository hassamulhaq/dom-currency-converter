document.addEventListener('DOMContentLoaded', function() {
/*		(function() {
            const send = XMLHttpRequest.prototype.send;
            XMLHttpRequest.prototype.send = function() {
                const self = this;
                const intervalId = window.setInterval(function () {
                    if (self.readyState !== 4) {
                        // execute your code here
                        console.log('XHR AJAX request is being sent...');
                    } else {
                        window.clearInterval(intervalId);
                    }
                    if (self.readyState === 4) {
                        //readFile("/openexchangeratesorg-aed.txt");
                    }
                    console.log('self.readyState', self.readyState);
                }, 1);
                send.apply(this, arguments);
			};
		})();*/
    // const paginationButton = document.querySelector('.pagination-button');
/*    paginationButton.addEventListener('click', function(event) {
        if (event.target.classList.contains('pagination-button')) {
            // Run code here for when the specific button is clicked
            console.log('Specific button clicked');
        }
    });*/


    //let rootPath = location.pathname.split('/').slice(0,2).join('/');
/*    let usd_to_aed_value = document.getElementById("openexchangeratesorg_usd_to_aed_value")?.value ?? 0;

    let priceCurrencyElements = document.querySelectorAll('.price-currency');
    priceCurrencyElements.forEach((element) => {
        element.textContent = 'AED';
    });
    // Loop through each span element and get its value
    let priceAmountElements = document.querySelectorAll('.price-amount');
    priceAmountElements.forEach((priceElement) => {
        let currentValue = parseFloat(priceElement.textContent.trim());
        let newValue = currentValue * usd_to_aed_value;
        priceElement.textContent = newValue.toFixed(2);
    });
    console.log('usd_to_aed_value', usd_to_aed_value);*/



    let usd_to_aed_value = 0;
    function readFile(filePath) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', filePath, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const fileData = xhr.responseText;
                // Do something with the file data here
                console.log(fileData); usd_to_aed_value = fileData;
                // Get all the <span> elements with the class "price-currency"
                let priceCurrencyElements = document.querySelectorAll('.price-currency');
                priceCurrencyElements.forEach((element) => {
                    element.textContent = 'AED';
                });
                // Loop through each span element and get its value
                let priceAmountElements = document.querySelectorAll('.price-amount');
                priceAmountElements.forEach((priceElement) => {
                    let currentValue = parseFloat(priceElement.textContent.trim());
                    let newValue = currentValue * usd_to_aed_value;
                    priceElement.textContent = newValue.toFixed(2);
                });
                console.log('usd_to_aed_value', usd_to_aed_value);
            }
        };
        xhr.send();
    }

    const openexchangeratesorgAedFilePath = openexchangeratesorg_data.openexchangeratesorg_aed_file_path;
    readFile(openexchangeratesorgAedFilePath);
});