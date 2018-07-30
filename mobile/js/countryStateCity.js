// --------------------------------------- Start Country State City Dropdown ----------------------------------------- //
function setStates() {
    cntrySel = document.getElementById('cmbCountry_id1');
    stateList = states[cntrySel.value];
    changeSelect('cmbState_id1', stateList, stateList);
    setCities();
}
function setCities() {
    cntrySel = document.getElementById('cmbCountry_id1');
    stateSel = document.getElementById('cmbState_id1');
    cityList = cities[cntrySel.value][stateSel.value];
    changeSelect('cmbCity_id1', cityList, cityList);
}
function changeSelect(fieldID, newOptions, newValues) {
    selectField = document.getElementById(fieldID);
    selectField.options.length = 0;
    for (i = 0; i < newOptions.length; i++) {
        selectField.options[selectField.length] = new Option(newOptions[i], newValues[i]);
    }
}
// Multiple onload function created by: Simon Willison
// http://simonwillison.net/2004/May/26/addLoadEvent/
function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function() {
            if (oldonload) {
                oldonload();
            }
            func();
        }
    }
}
addLoadEvent(function() {
//setStates();
});
// ---------------------------------------  end Country State City Dropdown ----------------------------------------- //