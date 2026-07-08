
        // Get the current date
        var currentDate = new Date();

        // Define an array of month names
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // Get the day, month, and year from the current date
        var day = currentDate.getDate();
        var month = monthNames[currentDate.getMonth()];
        var year = currentDate.getFullYear();

        // Create a formatted date string in the "d Month yyyy" format
        var formattedDate = day + ' ' + month + ' ' + year;

        // Display the formatted date in the specified element
        var dateDisplayElement = document.getElementById("dateDisplay");
        dateDisplayElement.textContent = formattedDate;

// Get copyright date
  document.getElementById("copyrightyear").textContent = new Date().getFullYear();


// Get card element equal height
    document.addEventListener('DOMContentLoaded', function() {
        function setEqualHeight() {
            // Select all rows
            var rows = document.querySelectorAll('.row');

            rows.forEach(function(row) {
                // Select all card-bg elements within the row
                var cardElements = row.querySelectorAll('.card-bg');

                // Find the maximum height within the row
                var maxHeight = 0;

                cardElements.forEach(function(card) {
                    var height = card.offsetHeight;
                    maxHeight = Math.max(maxHeight, height);
                });

                // Set the height of all card-bg elements within the row
                cardElements.forEach(function(card) {
                    card.style.height = maxHeight + 'px';
                });
            });
        }

        // Check window width on load and resize
        window.addEventListener('load', setEqualHeight);
        window.addEventListener('resize', function() {
            // Apply equal height logic only if the window width is ≥576px
            if (window.innerWidth >= 576) {
                setEqualHeight();
            } else {
                // Reset heights if window width is <576px
                var cardElements = document.querySelectorAll('.card-bg');
                cardElements.forEach(function(card) {
                    card.style.height = 'auto';
                });
            }
        });
    });
