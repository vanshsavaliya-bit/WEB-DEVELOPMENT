let events = [];
let currentPage = 1;
let perPage = 5;


// Fetch JSON data
fetch("events.json")
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed to load JSON");
        }
        return response.json();
    })
    .then(data => {
        events = data;
        document.getElementById("loading").style.display = "none";
        displayEvents();
    })
    .catch(error => {
        document.getElementById("loading").style.display = "none";
        document.getElementById("error").textContent =
            "Error loading events.";
        console.log(error);
    });


// Display events
function displayEvents() {

    let search = document.getElementById("search").value.toLowerCase();
    let filter = document.getElementById("filter").value;
    let sort = document.getElementById("sort").value;

    // Search
    let result = events.filter(event =>
        event.name.toLowerCase().includes(search)
    );

    // Filter
    if (filter !== "All") {
        result = result.filter(event =>
            event.category === filter
        );
    }

    // Sort
    if (sort === "az") {
        result.sort((a, b) =>
            a.name.localeCompare(b.name)
        );
    }

    if (sort === "za") {
        result.sort((a, b) =>
            b.name.localeCompare(a.name)
        );
    }


    // Pagination
    let start = (currentPage - 1) * perPage;
    let end = start + perPage;

    let pageData = result.slice(start, end);

    let html = "";

    pageData.forEach(event => {

        html += `
            <div class="card">
                <h3>${event.name}</h3>
                <p><b>Category:</b> ${event.category}</p>
                <p><b>Date:</b> ${event.date}</p>
                <p>${event.description}</p>
            </div>
        `;

    });

    document.getElementById("events").innerHTML =
        html || "<p>No events found.</p>";

    document.getElementById("page").textContent =
        "Page " + currentPage;

    // Button control
    document.getElementById("prev").disabled =
        currentPage === 1;

    document.getElementById("next").disabled =
        end >= result.length;
}


// Search
document.getElementById("search").addEventListener(
    "input",
    function () {
        currentPage = 1;
        displayEvents();
    }
);


// Filter
document.getElementById("filter").addEventListener(
    "change",
    function () {
        currentPage = 1;
        displayEvents();
    }
);


// Sort
document.getElementById("sort").addEventListener(
    "change",
    function () {
        displayEvents();
    }
);


// Previous
document.getElementById("prev").addEventListener(
    "click",
    function () {
        if (currentPage > 1) {
            currentPage--;
            displayEvents();
        }
    }
);


// Next
document.getElementById("next").addEventListener(
    "click",
    function () {
        currentPage++;
        displayEvents();
    }
);