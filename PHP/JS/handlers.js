
function updateFilteredView() {

    var loc_studentId = document.getElementById("locationSelector").value;
    var profiles = document.getElementById("profileSelector").value;
    var skills = document.getElementById("skillSelector").value;
    var availableStudent = document.getElementById("availabilitySelector").value;

    fetch("PHP/drivehandlers.php", {
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

function filterLocation() {
    var locSelector, selectedLoc, cards, card, locTag, i;
    locSelector = document.getElementById("locationSelector");
    selectedLoc = locSelector.value.trim().toUpperCase();
    console.log("selctedLoc: ", selectedLoc);
    cards = document.getElementsByClassName("student-card");
    sos = document.querySelector('.not-found');
    var matchFound = false;

    for (i = 0; i < cards.length; i++) {
        card = cards[i];
        locationTag = card.getElementsByTagName("span");
        locations = locationTag[2].textContent.trim().toUpperCase();
        console.log("locationssss: ", locations);

        if (locations.includes(selectedLoc) || selectedLoc == "" || selectedLoc == "TOUT LA FRANCE") {
            card.style.display = "";
            matchFound = true
        } else {
            card.style.display = "none";
        }
    }

    if (!matchFound) {
        sos.style.display = "none";
    } else {
        sos.style.display = "block";
    }
}

function filterByProfile() {
    var profileSelector, selectedProfile, cards, card, profileTag, spec_one, spec_two, i;
    profileSelector = document.getElementById("profileSelector");
    selectedProfile = profileSelector.value.trim().toUpperCase();
    //selectedProfile = profileSelector.value.toUpperCase();
    console.log("selectedProfile: ", selectedProfile);
    cards = document.getElementsByClassName("student-card");
    sos = document.querySelector('.not-found');
    var matchFound = false;

    for (i = 0; i < cards.length; i++) {
        card = cards[i];
        profileTag = card.getElementsByTagName("span");
        spec_one = profileTag[0].textContent.trim().toUpperCase();
        spec_two = profileTag[1].textContent.trim().toUpperCase();
        console.log("spec_one: ", spec_one);
        console.log("spec_two: ", spec_two);

        if (selectedProfile == "") {
            card.style.display = "";
            matchFound = true
        }

        if (spec_one.includes(selectedProfile) || spec_two.includes(selectedProfile) || selectedProfile == "") {
            card.style.display = "";
            matchFound = true
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

function filterBySkill() {
    var skillSelector, selectedSkill, cards, card, skillTag, i;
    skillSelector = document.getElementById("skillSelector");
    console.log("skillSelector: ", skillSelector);
    selectedSkill = skillSelector.value.trim().toUpperCase();
    cards = document.getElementsByClassName("student-card");
    console.log("selectedSkill", selectedSkill);
    sos = document.querySelector('.not-found');
    var matchFound = false;
    // Loop through each card
    for (i = 0; i < cards.length; i++) {
        card = cards[i];
        skillTag = card.getElementsByTagName("p");
        var skills = skillTag[1].textContent.trim().toUpperCase().split(",").map(skill => skill.trim());
        // Remove last skill if empty.
        if (skills[skills.length - 1] === "") {
            skills.pop();
        }
        
        // Separate first skill from icon
        var firstSkill = skills[0];
        var indexOfSpace = firstSkill.indexOf(" ");
        if (indexOfSpace !== -1) {
            skills[0] = firstSkill.substring(indexOfSpace + 1).trim();
        }

        for (var j = 0; j < skills.length; j++) {
            var skill = skills[j].toUpperCase();
            if (skill === selectedSkill || selectedSkill === "") {
                console.log("found-maola: ", skill);
                card.style.display = "";
                matchFound = true;
                break;
            } else {
                card.style.display = "none";
            }
        }
    }
    if (!matchFound) {
        sos.style.display = "block";
    } else {
        sos.style.display = "none";
    }
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

// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$

function filterStudents() {
    var locSelector = document.getElementById("locationSelector");
    var selectedLoc = locSelector.value.trim().toUpperCase();

    var profileSelector = document.getElementById("profileSelector");
    var selectedProfile = profileSelector.value.trim().toUpperCase();

    var skillSelector = document.getElementById("skillSelector");
    var selectedSkill = skillSelector.value.trim().toUpperCase();

    var cards = document.getElementsByClassName("student-card");
    var sos = document.querySelector('.not-found');
    var matchFound = false;

    for (var i = 0; i < cards.length; i++) {
        var card = cards[i];
        var locationTag = card.getElementsByTagName("span")[2].textContent.trim().toUpperCase();
        var profileTags = card.getElementsByTagName("span");
        var spec_one = profileTags[0].textContent.trim().toUpperCase();
        var spec_two = profileTags[1].textContent.trim().toUpperCase();
        var skillTags = card.getElementsByTagName("p")[1].textContent.trim().toUpperCase().split(",").map(skill => skill.trim());
        
        // Remove last skill if empty.
        if (skillTags[skillTags.length - 1] === "") {
            skillTags.pop();
        }
        // Separate first skill from icon
        var firstSkill = skillTags[0];
        var indexOfSpace = firstSkill.indexOf(" ");
        if (indexOfSpace !== -1) {
            skillTags[0] = firstSkill.substring(indexOfSpace + 1).trim();
        }

        var locationMatch = locationTag.includes(selectedLoc) || selectedLoc == ""; // || selectedLoc == "TOUT LA FRANCE"
        var profileMatch = selectedProfile == "" || spec_one.includes(selectedProfile) || spec_two.includes(selectedProfile);
        var skillMatch = selectedSkill == "" || skillTags.includes(selectedSkill);

        if (locationMatch && profileMatch && skillMatch) {
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
