function sendGetRequest(url, callback) {
    const xhr = new XMLHttpRequest();
  
    xhr.open('GET', url);
  
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
          // проверка типа ответа
          if (xhr.getResponseHeader('Content-Type') === 'application/json') {
            const response = JSON.parse(xhr.response);
            callback(response);
          } else {
            // обработка ответа
            const parser = new DOMParser();
            const doc = parser.parseFromString(xhr.response, 'text/html');
            console.log(doc);
            callback(doc); 
          }
        } else {
          console.error('Ошибка при получении данных:', xhr.status);
        }
      };
  
    xhr.onerror = function() {
      console.error('Ошибка при отправке запроса:', xhr.status);
    };
  
    xhr.send();
  }
  