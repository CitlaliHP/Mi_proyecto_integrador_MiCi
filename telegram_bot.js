// telegram_functions.js

function sendTelegramMessage(orden) {
    var configTelegram = {
        baseURL: 'https://api.telegram.org/bot',
        token: '6487124178:AAH4-H60WC-MEqnX0RjwPWKiPCNFOYaRm78',
        chat_id: '2135249412',
        parse_mode: 'MarkdownV2',
    };
    
    var mensaje = 'Alumno: ' + orden.matricula + '\n' + '. Preparar: ' + orden.productos + '. --> ' + orden.precioTotal; 
    var url = configTelegram.baseURL + configTelegram.token + '/sendMessage?chat_id=' + configTelegram.chat_id + '&text=' + mensaje;

    var connection = new XMLHttpRequest();
    connection.open("GET", url, true);
    connection.send();
    console.log('Orden enviada: ' + mensaje);
}