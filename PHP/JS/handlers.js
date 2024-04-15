

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

    var availabilitySelector = document.getElementById("availabilitySelector");
    var availabilities = availabilitySelector.value.trim().toUpperCase();
    // console.log("paaaarttts: ", availabilitySelector);
    var parts = availabilities.split(',');
    var selectedAvailability = parts[1];
    // console.log("availabilitySelected: ", selectedAvailability);

    var modeleSelector = document.getElementById("modeleSelector");
    var selectedModele = modeleSelector.value.trim().toUpperCase();

    var contratSelector = document.getElementById("contratSelector");
    var selectedContrat = contratSelector.value.trim().toUpperCase();
    
    var skillSelector = document.getElementById("skillSelector");
    var selectedSkill = skillSelector.value.trim().toUpperCase();
    console.log("selectedSkill: ", selectedSkill);

    // departmentSelector we are coming here
    var departmentSelector = document.getElementById("departmentSelector");
    var selectedDepartment = departmentSelector.value.trim().toUpperCase();
    console.log("selected department: ", selectedDepartment);

    var cards = document.getElementsByClassName("student-card");
    var sos = document.querySelector('.not-found');
    var matchFound = false;

    for (var i = 0; i < cards.length; i++) {
        var card = cards[i];
        var locationTag = card.querySelector('.locationTag').textContent.trim().toUpperCase();
        var europeTag = card.querySelector('.europeTag').textContent.trim().toUpperCase();
        // console.log('locate_tag: ', locationTag);
        var availabilityTag = card.querySelector('.availabilityTag').textContent.trim().toUpperCase();
        var departmentTag = card.querySelector('.departmentTag').textContent.trim().toUpperCase();
        var modeleTag = card.querySelector('.modeleTag').textContent.trim().toUpperCase();
        var contratsTag = card.querySelector('.contratsTag').textContent.trim().toUpperCase();
        // var skillTags = card.getElementsByTagName("p")[1].textContent.trim().toUpperCase().split(",").map(skill => skill.trim());
        var skillTags = card.querySelector('.skillTags').textContent.trim().toUpperCase().split(",").map(skill => skill.trim());
        // Remove last skill if empty.
        if (skillTags[skillTags.length - 1] === "") {
            skillTags.pop();
        }
        // Separate first skill from icon '⚙️'
        var firstSkill = skillTags[0];
        var indexOfSpace = firstSkill.indexOf(" ");
        if (indexOfSpace !== -1) {
            skillTags[0] = firstSkill.substring(indexOfSpace + 1).trim();
        }
        var profileTags = card.querySelectorAll('.profileTags');
        // iterate through specialisations to match each.
        for (var j=0; j < profileTags.length; j++) {
            var specials = profileTags[j].textContent.trim().toUpperCase();
            // console.log('profileTags: ', specials);

            var locationMatch = locationTag.includes(selectedLoc) || selectedLoc == "" || selectedLoc == europeTag; // || selectedLoc == "TOUT LA FRANCE"
            var specialityMatch = selectedProfile == "" || specials.includes(selectedProfile);
            var skillMatch = selectedSkill == "" || skillTags.includes(selectedSkill);
            var departmentMatch = selectedDepartment == "" || departmentTag == selectedDepartment;
            var modeleMatch = selectedModele == "" || modeleTag.includes(selectedModele) ; // NOTE: || europeTag == 1 can be removed if need be. it is there because are assuming any student willing to work all  through europe is authomatically open for teletravail
            var contratsMatch = selectedContrat == "" || contratsTag.includes(selectedContrat);
            var availabilityMatch = selectedAvailability == "" || availabilityTag.includes(selectedAvailability) ;

            if (locationMatch && specialityMatch && skillMatch && availabilityMatch && departmentMatch && modeleMatch && contratsMatch) {
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
}

// function viewVideo(studentId) {
//     var url = 'route123/user-video.php?student_id=' + studentId;
//     window.location.href = url;
// }

function  viewUpload(studentId, usercv) {
    var url = 'route123/' + usercv +'.php?student_id=' + studentId;
    window.location.href = url;
}

function limitSpecialisations() {
    var checkboxes = document.querySelectorAll('input[name="specialisations[]"]');
    var checkedCount = 0;
    for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked) {
        checkedCount++;
      }
    }
    if (checkedCount > 4) {
      alert("You can select only up to 4 specializations.");
      return false;
    }
}