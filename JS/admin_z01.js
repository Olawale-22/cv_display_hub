function action(){
	let selectorStudent = document.getElementById("selectorStudent");
	let selectorMonth = document.getElementById("selectorMonth");
	let selectorYear = document.getElementById("selectorYear");

	let iS = selectorStudent.selectedIndex;
	let iM = selectorMonth.selectedIndex;
	let iY = selectorYear.selectedIndex;

	//Checking if month < 10, if so, we add a leading zero to make the good API call
	if(iM < 10){
		iM = "0" + iM;
	}

	if(selectorStudent.options[iS].value == '*'){
		fetch("API/students")
		.then(function(res) {
			if (res.ok) {
				return res.json();
			}
		})
		.then(function(value) {
			console.log(value);
		})
		.catch(function(err){
			console.log(err);
		});
	} else {
	// EX : "API/student/[pseudoStudent]/[YYYY]/[MM]"
	// Retrieving pseudo from selected dropdown menu
		let regExp = /\(([^)]+)\)/;
		let matches = regExp.exec(selectorStudent.options[iS].text);
		if(matches) {
			let studentPseudo = matches[1];

			fetch("/sign_iscom/API/student/" + studentPseudo + "/" + selectorYear.options[iY].value + "/" + iM)
			.then(function(res) {
				if (res.ok) {
					return res.json();	
				}
			})
			.then(function(value) {
				removeAllChild();
				if(selectorStudent.selectedIndex != 0){
					let addLogIdStudent = document.getElementById('studentId');
					addLogIdStudent.value = selectorStudent.value;
				}			

				createAllChildBeta(value);
				retrieveDataRows();
				
				nbJours(value);
				nbHeures(value);
				
			})
			.catch(function(err){
				console.log(err);
			});
		}
	}
}

// Function that allows to automatically set month and year on the admin panel
function setActualDate(){
	var currentDay = new Date();
	var monthsDropdown = document.getElementById('selectorMonth');
	monthsDropdown.selectedIndex = currentDay.getMonth() + 1;
	var yearDropdown = document.getElementById('selectorYear');
	yearDropdown.selectedIndex = currentDay.getFullYear() - 2021;
}

// Fonction qui permet de supprimer tous les enfant de la table (tr et td) pour les horraires
// Elle sera appellé a chaque fois qu'un selecteur de la page admin sera changé
// Permet de refresh completement les enfants de la table

function removeAllChild(){
	let doc = document.getElementById("tbody")
	let doc2 = document.getElementById("nb_heure")
	doc2.innerHTML = "0";
	while (doc.firstChild) {
		doc.removeChild(doc.lastChild);
	}
}

// Fonction qui permet de créer tous les enfant de la table (tr et td) pour les horraires
// Ex : <tr><td>...</td><td>...</td>...</tr>
// Elle sera appellé a chaque fois qu'un selecteur de la page admin sera changé

function createAllChild(value){
	//console.log(value);
	value['logs'].forEach(function(row){
		tbody = document.getElementById("tbody");

		const newElt = document.createElement("tr")
		newElt.setAttribute("id", "table_tr");
		for(var r in row){
			let tdElt = document.createElement("td");
			if(r == 'id' || r == 'pseudo'){
				continue;
			} else if(r == 'enterDate'){
				let result = row[r].substring(11,16);
				tdElt.textContent = `${result}`;
			} else if(r == 'exitDate'){
				let result;
				if (!row[r]){
					result = "";
				} else {
					result = row[r].substring(11,16);
				}
				tdElt.textContent = `${result}`;
			} else if(r == 'timeIn'){
				if(!row[r]){
					result = ""
				} else {
					result = row[r];
				}
				tdElt.textContent = `${result}`;
				
			}
			else {
				tdElt.textContent = `${row[r]}`;
			}
			newElt.appendChild(tdElt);
		}


		tbody.appendChild(newElt);
	});
}

// Fonction BETA, permet de modifier directement les infos sur la page

function createAllChildBeta(value){
	console.log(value);
	value['logs'].forEach(function(row){
		tbody = document.getElementById("tbody");

		const newElt = document.createElement("tr")
		newElt.setAttribute("id", "table_tr");
		for(var r in row){
			let tdElt = document.createElement("td");
			if(r == 'id' || r == 'pseudo'){
				continue;
			} else if(r == 'enterDate'){
				let result = row[r].substr(11);
				tdElt.textContent = `${result}`;
			} else if(r == 'exitDate'){
				let result;
				if (!row[r]){
					result = "";
				} else {
					result = row[r].substr(11);
				}
				tdElt.textContent = `${result}`;
			} else if(r == 'timeIn'){
				if(!row[r]){
					result = ""
				} else {
					result = row[r];
				}
				tdElt.textContent = `${result}`;
				
			} else if(r =='userIp'){
				tdElt.classList.add("hideIp");
				tdElt.setAttribute("name","ip");
				if(row[r] != null){
					tdElt.textContent = `${row[r]}`;
				}
			} else {
				tdElt.textContent = `${row[r]}`;
			}
			newElt.appendChild(tdElt);
		}
		tbody.appendChild(newElt);
	});
}




function nbJours(value){
	let elt = document.getElementById('nb_jour_present');
	let sum = 0;
	value['logs'].forEach(function(){
		sum++
	});
	elt.innerHTML = sum;

}

// Fonction qui permet de renvoyer le nombre d'heure passée 
// durant le mois sélectionné. Le nombre d'heure est arrondi selon
// les règles d'arrondissement de base.
function nbHeures(value){
	let e = document.getElementById("nb_heure");
	if(!value['hour_passed']){
		return;
	}
	let hour = parseInt(value['hour_passed'].split(':')[0], 10);
	let minOfHour = parseInt(value['hour_passed'].split(':')[1], 10);

	if(minOfHour >= 30){
		e.innerHTML = `${hour + 1}`
	} else {
		e.innerHTML = `${hour}`
	}
}

// Function that allows rows to be clickable to retrieve datas from them
function retrieveDataRows(){

	let selectorStudent = document.getElementById("selectorStudent");
	let selectorMonth = document.getElementById("selectorMonth");
	let selectorYear = document.getElementById("selectorYear");

	let idStudentForm = document.getElementById('idStudent');
	let oldDate = document.getElementById('oldDate');
	let oldHourEnter = document.getElementById('oldHourEnter');
	let oldHourExit = document.getElementById('oldHourExit');
	let actHour = document.getElementById('actHourEnterEdit')

//For Edit log part
	let selectedIndexStudent = document.getElementById('selectedIndexStudent');
	let selectedIndexMonth = document.getElementById('selectedIndexMonth');
	let selectedIndexYear = document.getElementById('selectedIndexYear');

//For Adding log part
	let selectedIndexStudentAdd = document.getElementById('selectedIndexStudentAdd');
	let selectedIndexMonthAdd = document.getElementById('selectedIndexMonthAdd');
	let selectedIndexYearAdd = document.getElementById('selectedIndexYearAdd');

// If all selectors are not default, we do action.
	if(selectorStudent.selectedIndex != 0 && selectorMonth.selectedIndex != 0 && selectorYear.selectedIndex != 0){
		var table= document.querySelector('table');
		var rows= table.getElementsByTagName('tr');
		var idStudent = selectorStudent.value;
		var rowData= [];

// Edit form
		selectedIndexStudent.value = selectorStudent.selectedIndex;
		selectedIndexMonth.value = selectorMonth.selectedIndex;
		selectedIndexYear.value = selectorYear.selectedIndex;

// Add form
		selectedIndexStudentAdd.value = selectorStudent.selectedIndex;
		selectedIndexMonthAdd.value = selectorMonth.selectedIndex;
		selectedIndexYearAdd.value = selectorYear.selectedIndex;

		for(i= 0;i<rows.length;i++){
			rows[i].onclick= function(){
				rowIdx= this.rowIndex;
				selectedRow= this.cells;
				rowData.push(idStudent);

				for(j= 0;j<selectedRow.length;j++){
					rowCellValue= selectedRow[j].textContent || selectedRow[j].innerText;
					rowData.push(rowCellValue);
				}
            //console.log(rowData);

				idStudentForm.value = rowData[0];
				oldDate.value = rowData[1];
				oldHourEnter.value = rowData[2];
				oldHourExit.value = rowData[3]; 
				actHour.value = rowData[2];
				rowData = [];
			}
		}
	}
}

let ipList = document.getElementsByName('ip');

document.addEventListener('keydown', (event) => {
  var name = event.key;
  var code = event.code;
  // Alert the key name and key code on keydown
    if(code == "KeyX"){
			for (let item of ipList) {
			    item.classList.toggle("hideIp");
			}
    }
}, false);
