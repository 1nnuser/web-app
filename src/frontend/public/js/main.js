const dateInput = document.getElementById('date');
const dateInput1 = document.getElementById('date-search');

const regionSelect = document.getElementById('region'); 
const couriersDiv = document.getElementById('couriers');
const couriersFreeSpan = document.getElementById('couriers-free');
const couriersRangeSpan = document.getElementById('couriers-range');

const couriersTable = document.getElementById('couriers-table');
const courierSelect = document.getElementById('courier-select');
const tableBody = document.querySelector('#couriers-table tbody');
const allCountRecSpan = document.getElementById('all-count-record');
const selectSortSelect = document.getElementById('select-sort');
const submitDelivery = document.getElementById('sumbit-delivery');
const startSearch = document.getElementById('start-search');
const countInputT = document.getElementById('count-delivery');


let dateChanged = false; // Отслеживаем изменение даты
let regionChanged = false; // Отслеживаем изменение региона

updateCouriersTable();

submitDelivery.addEventListener('click', () => {
    sendFormDelivery();
    checkInputs(true);
});

countInputT.addEventListener('change', () => {
    updateCouriersTable();
});

dateInput.addEventListener('change', () => {
    dateChanged = true;
    checkInputs(); 
});

regionSelect.addEventListener('change', () => {
    regionChanged = true;
    checkInputs();
});

selectSortSelect.addEventListener('change', () => {
    updateCouriersTable();
});

selectSortSelect.addEventListener('change', () => {
    updateCouriersTable();
});
dateInput1.addEventListener('change', () => {
    updateCouriersTable();
});

let tripEnd;
let tripStart;
function sendFormDelivery(){
    if(courierSelect.value !== ""){
        let data = {
            "tripStart": tripStart,
            "tripEnd": tripEnd,
            "courier": courierSelect.value,
            "region": regionSelect.value
        };
        sendPostRequest('http://localhost/api/deliveries', data, function(response) {
            console.log(response); 
          });
        
    }
    else{
        alert('Вы не можете реализовать поставку без курьера!');
    }
}

function checkInputs(status) {
    if (dateChanged || regionChanged) {
        let dateValue = dateInput.value;
        let regionValue = regionSelect.value;
        let tripData; 

        sendGetRequest(`http://localhost/api/couriers?city=${regionValue}&date=${dateValue}`, function(response){
            tripData = response; 
            let couriers = tripData.message.couriers; 
            let rangeDate = tripData.message.date; 

            console.log(`${couriers} ${rangeDate}`);
            console.log('Данные записаны в tripData:', tripData);

            courierSelect.innerHTML = '<option value="">Выберите курьера</option>';
            couriers.forEach(courier => {
                const option = document.createElement('option');
                option.value = courier.id; 
                option.text = courier.full_name; 
                courierSelect.appendChild(option);
            });
            tripEnd = rangeDate.E;
            tripStart = rangeDate.S;
            couriersFreeSpan.textContent = `Свободных курьеров: ${couriers.length}`; 
            couriersRangeSpan.textContent = `Курьер будет занят: с ${rangeDate.S} по ${rangeDate.E}`;
        });

        // сброс флагов
        dateChanged = false;
        regionChanged = false;

    }
}

function updateCouriersTable() {
    let sortValue = selectSortSelect.value;
    let countValue = countInputT.value;
    let dateDelivery = dateInput1.value;
    console.log(dateDelivery);
    sendGetRequest(`http://localhost/api/deliveries?date=${dateDelivery}&offset=0&amount=${countValue}`, function(response){
        let tripData = response; 
        let couriers = tripData.message.deliveries; // записываем свободных курьеров 
        let totalDelivery = tripData.message.total[0].Total; 
        allCountRecSpan.textContent = `Всего записей -> ${totalDelivery}`; 

        tableBody.innerHTML = ''; 

        couriers.forEach((courier, index) => {
            const row = tableBody.insertRow();
            row.insertCell().textContent = index + 1;
            row.insertCell().textContent = courier.fullname;
            row.insertCell().textContent = courier.courier_id; 
            row.insertCell().textContent = courier.region_name; 
            row.insertCell().textContent = courier.start; 
            row.insertCell().textContent = courier.end; 
            row.insertCell().textContent = courier.status; 
        });
    });
}