
function updateFilteredView() {

    var loc_studentId = document.getElementById("locationSelector").value;
    var profiles = document.getElementById("profileSelector").value;
    var skills = document.getElementById("skillSelector").value;
    var availableStudent = document.getElementById("availabilitySelector").value;

    // Send an AJAX request to fetch filtered data
    fetch("./drivehandlers.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "&locate_sid=" + loc_studentId + "&dispo_sid=" + availableStudent + "&skill_id=" + skills + "&profile_id=" + profiles
    })

    .then(function(response) {
// console.log("responseFilteredDate: ", response);
        if (response.ok) {
            return response.text();
        } else {
            throw new Error("Error: " + response.status);
        }
    })
    .then(function(data) {
// console.log("dataFilteredData: ", data);
        var jsonData = JSON.parse(data);

        displayRows(jsonData);
    })
    .catch(function(error) {
        console.error(error);
    });
}

function search() {
    var input, filter, cards, card, name, i, profile, spec_one, spec_two, location;
    input = document.getElementById("searchBox");
    filter = input.value.trim().toUpperCase();
    cards = document.getElementsByClassName("student-card");
    sos = document.querySelector('.not-found');

    var matchFound = false;
    
    for (i = 0; i < cards.length; i++) {
        card = cards[i];
        name = card.getElementsByTagName("h3")[0];
        profile = card.getElementsByTagName("span");
        spec_one = profile[0].textContent.trim().toUpperCase();
        spec_two = profile[1].textContent.trim().toUpperCase();
        location = profile[2].textContent.trim().toUpperCase();

        var lines = name.innerText.split(/\r?\n/);
        var nomValue = lines[0].trim().toUpperCase();
        var prenomValue = lines[1].trim().toUpperCase();

        if (filter === "") {
            card.style.display = "";
            matchFound = true;
        }

        if (nomValue.includes(filter) || prenomValue.includes(filter) || spec_one.includes(filter) || spec_two.includes(filter) || location.includes(filter)) {
            card.style.display = "";
            matchFound = true;
        } else {
            card.style.display = "none";
        }
    }
    
    if (!matchFound) {
        sos.style.display = "block";
    } else {
        sos.style.display = "none";
    }
}
