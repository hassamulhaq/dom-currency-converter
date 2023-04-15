document.addEventListener('DOMContentLoaded', function() {
    let usd_to_aed_value = 0;
    function readFile(filePath) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', filePath, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const fileData = xhr.responseText;
                // Do something with the file data here
                console.log("Today 1USD is equal to " + fileData + "AED" ); //usd_to_aed_value = fileData;
                // Get all the <span> elements with the class "price-currency"
                /*let priceCurrencyElements = document.querySelectorAll('.price-currency');
                priceCurrencyElements.forEach((element) => {
                    element.textContent = 'AED';
                });
                // Loop through each span element and get its value
                let priceAmountElements = document.querySelectorAll('.price-amount');
                priceAmountElements.forEach((priceElement) => {
                    let currentValue = parseFloat(priceElement.textContent.trim());
                    let newValue = currentValue * usd_to_aed_value;
                    priceElement.textContent = newValue.toFixed(2);
                });*/

                // Get all the elements with the class "normal-price"
                const normalPrices = document.querySelectorAll('.normal-price');
                // Loop through each normal price element
                normalPrices.forEach(normalPrice => {
                    // Get the price-currency and price-amount elements for this normal price
                    const currency = normalPrice.querySelector('.price-currency');
                    const amount = normalPrice.querySelector('.price-amount');
                    if (currency.textContent === 'USD') {
                        currency.textContent = 'AED';
                        amount.textContent = (amount.textContent.trim() * usd_to_aed_value).toFixed(2);
                    } else {
                        console.log('Currency is Already in AED. No need to convert.');
                    }
                });
                console.log('usd_to_aed_value', usd_to_aed_value);
            }
        };
        xhr.send();
    }

    const openexchangeratesorgAedFilePath = openexchangeratesorg_data.openexchangeratesorg_aed_file_path;
    readFile(openexchangeratesorgAedFilePath);


    /**
     * Interval with 3s is required
     * because of ajax pagination and room loads
     *
     */
    // Trigger the readFile() function every 3 seconds
    setInterval(function() {
        console.log('DOM Currency Converter Interval.');
        readFile(openexchangeratesorgAedFilePath);
    }, 3000);
});