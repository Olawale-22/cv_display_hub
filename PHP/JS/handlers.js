

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
        // Separate first skill from icon ⚙️
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

function viewVideo(studentId) {
    var url = 'route123/user-video.php?student_id=' + studentId;
    window.location.href = url;
}
