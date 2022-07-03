const main = document.getElementById("main");
const info = document.getElementById("info");
const stats = document.getElementById("stats");

const table1 = document.getElementById("table1").tBodies[0];
const table2 = document.getElementById("table2").tBodies[0];
const table3 = document.getElementById("table3").tBodies[0];
const div = document.getElementById("tableGang");

const name = document.getElementById("name").textContent;
const code = document.getElementById("code").textContent;
const lat = document.getElementById("lat").textContent;
const lon = document.getElementById("lon").textContent;

let set1 = false;
let set2 = false;

function clickMain(){
    changeWindow(main, info, stats);
}

function clickInfo(){
    changeWindow(info, main, stats);
    setMap();
}

function clickStats(){
    changeWindow(stats, info, main);
    setStatMap();
}

function changeWindow(a, b, c){
    a.style.display = "block";
    b.style.display = "none";
    c.style.display = "none";
}

getFetch();

function getFetch() {

    table1.innerHTML = "";
    table2.innerHTML = "";
    table3.innerHTML = "";

    fetch("info.php", {method: "get"})
        .then(response => response.json())
        .then(result => {
            result.forEach(item => {
                if(item['code'] === code){
                    const tr = document.createElement("tr");
                    const td1 = document.createElement("td");
                    const td2 = document.createElement("td");
                    const td3 = document.createElement("td");

                    td1.append(name);
                    td2.append(code);
                    td3.append(item['capital']);

                    tr.append(td1);
                    tr.append(td2);
                    tr.append(td3);

                    table1.append(tr);
                }
            })
        })

    fetch("stat.php", {method: "get"})
        .then(response => response.json())
        .then(result => {
            result.forEach(item => {
                const tr = document.createElement("tr");
                const td1 = document.createElement("td");
                const td2 = document.createElement("td");
                const td3 = document.createElement("td");
                const img = document.createElement("img");
                const src = "https://www.geonames.org/flags/x/" + item['country'].toLowerCase() + ".gif";

                img.setAttribute("src", src);
                img.setAttribute("alt", "image");
                img.setAttribute("height", "50");
                img.setAttribute("width", "75");
                img.style.cursor = "pointer";

                let tbl = false;
                img.addEventListener('click', () => {
                    if(!tbl) {
                        tbl = true;

                        const table = document.createElement("table");
                        const thead = document.createElement("thead");
                        const tr = document.createElement("tr");
                        const th1 = document.createElement("th");
                        const th2 = document.createElement("th");
                        const tbody = document.createElement("tbody");

                        th1.append("City");
                        th2.append("Visits");
                        tr.append(th1);
                        tr.append(th2);
                        thead.append(tr);
                        table.append(thead);

                        fetch("stat2.php?code=" + item['country'], {method: "GET",})
                            .then(response => response.json())
                            .then(result => {
                                result.forEach(items => {
                                    const tr1 = document.createElement("tr");
                                    const td1 = document.createElement("td");
                                    const td2 = document.createElement("td");

                                    td1.append(items['city']);
                                    td2.append(items['count']);

                                    tr1.append(td1);
                                    tr1.append(td2);
                                    tbody.append(tr1);
                                })
                            })

                        table.append(tbody);
                        table.setAttribute("class", "marginbottom");
                        table.style.cursor = "pointer";
                        div.append(table);
                        table.addEventListener('click', () => {
                            table.innerHTML = "";
                            tbl = false;
                        })
                    }
                })

                td1.append(img);
                td2.append(item['country']);
                td3.append(item['visits']);

                tr.append(td1);
                tr.append(td2);
                tr.append(td3);

                table2.append(tr);
            })
        })

    fetch("time.php", {method: "get"})
        .then(response => response.json())
        .then(result => {
            let i = 0;
            result.forEach(item => {
                const tr = document.createElement("tr");
                const td1 = document.createElement("td");
                const td2 = document.createElement("td");

                switch (i) {
                    case 0:
                        td1.append("6 - 15");
                        break;
                    case 1:
                        td1.append("15 - 21");
                        break;
                    case 2:
                        td1.append("21 - 24");
                        break;
                    case 3:
                        td1.append("0 - 6");
                        break;
                }

                td2.append(item[0]['visits']);

                tr.append(td1);
                tr.append(td2);

                table3.append(tr);
                i++;
            })
        })
}

function setMap() {
    if(!set1) {
        set1 = true;

        let map = L.map('map').setView([lat, lon], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([lat, lon]).addTo(map)
    }
}

function setStatMap() {
    if(!set2) {
        set2 = true;

        let map = L.map('map2').setView([0, 0], 1);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        fetch("latlon.php", {method: "get"})
            .then(response => response.json())
            .then(result => {
                result.forEach(item => {
                    L.marker([item['lat'], item['lon']]).addTo(map)
                })
            })
    }
}