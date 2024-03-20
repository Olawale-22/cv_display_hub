function showInfoStudent(student, month = new Date().getMonth() + 1, year = new Date().getFullYear()){
	//Checking if month < 10, if so, we add a leading zero to make the good API call
    if(month < 10){
            month = "0" + month;
        }
        fetch("/sign_iscom/API/student/" + student + "/" + year + "/" + month )
        .then(function(res) {
        if (res.ok) {
            return res.json();
        }
    })
        .then(function(value) {
            createAllChild(value);

        })
        .catch(function(err){
        console.log(err);
    });
}

function createAllChild(value){
    value['logs'].forEach(function(row){
        const tbody = document.getElementById("tbody");
        const newElt = document.createElement("tr")
        newElt.setAttribute("id", "table_tr");
        for(var r in row){
            let tdElt = document.createElement("td");
            console.log(r);
            if(r == 'id' || r == 'pseudo' || r == 'logsId'){
                continue;
            } else if(r == 'enterDate'){
                let result = row[r].substring(11,16);
                tdElt.textContent = `${result}`;
            } else if(r == 'exitDate'){
                let result;
                if (!row[r]){
                    result = "";
                } else {
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

            //using this to check what Ip is used
            if(r != 'userIp'){
            newElt.appendChild(tdElt);
            }
        }
        tbody.appendChild(newElt);
    });
}

//Take two times "hh:mm:ss" and sums them
function sumTime(date1, date2){
  date1 = date1.split(":");
  date2 = date2.split(":");
  const result = [];

  date1.reduceRight((carry,num, index) => {
    const max = [24,60,60][index];
    const add =  +date2[index];
    result.unshift( (+num+add+carry) % max );
    return Math.floor( (+num + add + carry) / max );
},0);

  return result.join(":");
}

function changeUserSelection(student){
    let userDate = document.getElementById('userSelectionDate');
    let date = new Date(userDate.value);
    let tbody = document.getElementById("tbody");
    let hrsByWeek = document.getElementById("byHours");
    if(tbody.children.length != 0){
        tbody.innerHTML = "";
    }
    if(hrsByWeek.children.length != 0){
        hrsByWeek.innerHTML = "";
    }

    showInfoStudent(student, date.getMonth() + 1, date.getFullYear());
}