document.addEventListener('DOMContentLoaded', function () {
    
    const hash = window.location.hash;
    if(hash) {
        const targetSection = document.querySelector(hash);
        if(targetSection) {
            document.querySelectorAll('.section-view').forEach(s => s.classList.remove('active'));
            targetSection.classList.add('active');

            const btn = document.querySelector(`.nav-button[data-target="${hash.substring(1)}"]`);
            if(btn) {
                document.querySelectorAll('.nav-button').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }
        }
    }


    const navButtons = document.querySelectorAll('.nav-button');
    const sections = document.querySelectorAll('.section-view');

    navButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            navButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            sections.forEach(sec => sec.classList.remove('active'));

            const targetId = btn.getAttribute('data-target');
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });

    const currentRoleBadge = document.getElementById('currentRole');

    function applyRole(role) {
        if (currentRoleBadge) {
            currentRoleBadge.textContent = role;
        }

        document.body.classList.remove('role-admin', 'role-staff', 'role-student');

        if (role === 'admin' || role === 'staff' || role === 'student') {
            document.body.classList.add('role-' + role);
        } else {
            document.body.classList.add('role-student');
        }
    }

    let roleFromStorage = localStorage.getItem('userRole');
    if (!roleFromStorage) {
        roleFromStorage = 'admin';
    }
    applyRole(roleFromStorage);

    let userName = localStorage.getItem('userName') || 'User';
    const welcomeUser = document.getElementById('welcomeUser');
    if (welcomeUser) {
        welcomeUser.textContent = 'Hi ' + userName;
    }

    const logoutBtn = document.getElementById('btn-logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            localStorage.removeItem('userRole');
            localStorage.removeItem('userName');
            window.location.href = 'login.html';
        });
    }

    if (typeof Chart !== 'undefined') {
        const ctxBooksCat = document.getElementById('chartBooksByCategory');
        if (ctxBooksCat) {
            const booksByCategoryData = {
                labels: categoryDistribution ? Object.keys(categoryDistribution) : ['Category1', 'Category2', 'Category3', 'Category4', 'Category5'],
                datasets: [{
                    label: 'Books',
                    data: categoryDistribution ? Object.values(categoryDistribution) : [12, 19, 7, 5, 10]
                }]
            };

            new Chart(ctxBooksCat, {
                type: 'bar',
                data: booksByCategoryData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        const ctxLoansMonth = document.getElementById('chartLoansPerMonth');
        if (ctxLoansMonth) {
            const loansPerMonthData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Loans',
                    data: [loansPerMonth[0] || 0, loansPerMonth[1] || 0, loansPerMonth[2] || 0, loansPerMonth[3] || 0, loansPerMonth[4] || 0, loansPerMonth[5] || 0, loansPerMonth[6] || 0, loansPerMonth[7] || 0, loansPerMonth[8] || 0, loansPerMonth[9] || 0, loansPerMonth[10] || 0, loansPerMonth[11] || 0]
                }]
            };
           
            new Chart(ctxLoansMonth, {
                type: 'line',
                data: loansPerMonthData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    tension: 0.3,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    }

    const statTotalBooks = document.getElementById('statTotalBooks');
    const statAvailableBooks = document.getElementById('statAvailableBooks');
    const statBorrowers = document.getElementById('statBorrowers');
    const statActiveLoans = document.getElementById('statActiveLoans');

    if (statTotalBooks) statTotalBooks.textContent = numTotalBooks;
    if (statAvailableBooks) statAvailableBooks.textContent = numAvailableBooks;
    if (statBorrowers) statBorrowers.textContent = numTotalBorrowers;
    if (statActiveLoans) statActiveLoans.textContent = numActiveLoans;

    const reportTotalBooksValue = document.getElementById('reportTotalBooksValue');
    const reportAvailableBooksCount = document.getElementById('reportAvailableBooksCount');
    const reportBooksPerCategoryTable = document.getElementById('reportBooksPerCategory');

    if (reportTotalBooksValue) reportTotalBooksValue.textContent = '1500.00 $';
    if (reportAvailableBooksCount) reportAvailableBooksCount.textContent = '20';

    if (reportBooksPerCategoryTable) {
        const tbody = reportBooksPerCategoryTable.querySelector('tbody');

        if (tbody) {
            const demoRows = [
                { category: 'Category1', count: 6 },
                { category: 'Category2', count: 5 },
                { category: 'Category3', count: 7 },
                { category: 'Category4', count: 4 },
                { category: 'Category5', count: 8 }
            ];

            tbody.innerHTML = '';
            demoRows.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.category}</td>
                    <td>${row.count}</td>
                `;
                tbody.appendChild(tr);
            });
        }
    }

       const tableBooks = document.getElementById('tableBooks');
    if (tableBooks) {
        const tbody = tableBooks.querySelector('tbody');
        if (tbody) {
            const demoBooks =allBooks;

            tbody.innerHTML = '';
            demoBooks.forEach(book => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${book.book_id}</td>
                    <td>${book.title}</td>
                    <td>${book.publisher_id}</td>
                    <td>${book.category}</td>
                    <td>${book.book_type}</td>
                    <td>${book.original_price}</td>
                    <td>${book.available >0?'Yes' : 'No'}</td>
                    <td class="admin-only">
                        <button class="btn btn-sm btn-warning me-1 btn-edit" data-id="${book.book_id}">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${book.book_id}">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }}
        let str = '';
        for(let publisher of allPublisher) {
            console.log(publisher);
            str += `<option value="${publisher.publisher_id}">${publisher.name}</option>`;
        }
        let selectPublisher = document.getElementById('publisherSelect');
        selectPublisher.innerHTML = `<select class="form-select" name="publisher_id" id="publisherSelect">` + 
                                        `<option value="">Select Publisher</option>` + str + `</select>`;});
const modal = document.getElementById("modalBook");
const overlay = document.getElementById("overlay");
const closeBtn = document.querySelector(".close");

document.getElementById('tableBooks').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-edit')) {
        const bookId = e.target.getAttribute('data-id');
        document.getElementById('bookId').value = bookId;
        console.log("Editing book ID:",  e.target.getAttribute('data-id'));
        modal.style.display = 'block';
        overlay.style.display = 'block';
    }
});



closeBtn.addEventListener('click', () => {
    modal.style.display = "none";
    overlay.style.display = "none";
});

overlay.addEventListener('click', () => {
    modal.style.display = "none";
    overlay.style.display = "none";
});
const overlayDelete = document.getElementById("overlayDelete");
const modalDeleteBook = document.getElementById("modalDeleteBook");
const closeModalDeleteBtn = document.getElementById("closeModalDeleteBook");
const btnCancelDelete = document.getElementById("btnCancelDelete");

document.getElementById('tableBooks').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-delete')) {
        const bookId = e.target.getAttribute('data-id');
        document.getElementById('deleteBookId').value = bookId;
        modalDeleteBook.style.display = 'block';
        overlayDelete.style.display = 'block';
    }
});

closeModalDeleteBtn.addEventListener('click', () => {
    modalDeleteBook.style.display = "none";
    overlayDelete.style.display = "none";
});
btnCancelDelete.addEventListener('click', () => {
    modalDeleteBook.style.display = "none";
    overlayDelete.style.display = "none";
});

overlayDelete.addEventListener('click', () => {
    modalDeleteBook.style.display = "none";
    overlayDelete.style.display = "none";
});
const publisherModal = document.getElementById("publisherModel");
const closePublisherBtn = document.getElementById("closePublisherModal");


closePublisherBtn.addEventListener("click", () => {
    publisherModal.style.display = "none";
});




    const tableAuthors = document.getElementById('tableAuthors');
    if (tableAuthors) {
        const tbody = tableAuthors.querySelector('tbody');
        if (tbody) {
            const demoAuthors = allAuthors;

            tbody.innerHTML = '';
            demoAuthors.forEach(a => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="author_id">${a.author_id}</span></td>
                    <td><span class="first_name">${a.first_name}</span></td>
                    <td><span class="last_name">${a.last_name}</span></td>
                    <td><span class="country">${a.country}</span></td>
                    <td><span class="bio">${a.bio}</span></td>
                    <td class="admin-only">
                        <button class="btn btn-sm btn-warning me-1 btn-edit" data-id="${a.auhtor_id}">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }
  /**let str = '';
        for(let publisher of allPublisher) {
            console.log(publisher);
            str += `<option value="${publisher.publisher_id}">${publisher.name}</option>`;
        }
        let selectPublisher = document.getElementById('publisherSelect');
        selectPublisher.innerHTML = `<select class="form-select" name="publisher_id" id="publisherSelect">` + 
                                        `<option value="">Select Publisher</option>` + str + `</select>`;});
const modal = document.getElementById("modalBook");
const overlay = document.getElementById("overlay");
const closeBtn = document.querySelector(".close");

document.getElementById('tableBooks').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-edit')) {
        const bookId = e.target.getAttribute('data-id');
        document.getElementById('bookId').value = bookId;
        console.log("Editing book ID:",  e.target.getAttribute('data-id'));
        modal.style.display = 'block';
        overlay.style.display = 'block';
    }
});



closeBtn.addEventListener('click', () => {
    modal.style.display = "none";
    overlay.style.display = "none";
});

overlay.addEventListener('click', () => {
    modal.style.display = "none";
    overlay.style.display = "none";
});
const overlayDelete = document.getElementById("overlayDelete");
const modalDeleteBook = document.getElementById("modalDeleteBook");
const closeModalDeleteBtn = document.getElementById("closeModalDeleteBook");
const btnCancelDelete = document.getElementById("btnCancelDelete");

document.getElementById('tableBooks').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-delete')) {
        const bookId = e.target.getAttribute('data-id');
        document.getElementById('deleteBookId').value = bookId;
        modalDeleteBook.style.display = 'block';
        overlayDelete.style.display = 'block';
    }
});

closeModalDeleteBtn.addEventListener('click', () => {
    modalDeleteBook.style.display = "none";
    overlayDelete.style.display = "none";
});
btnCancelDelete.addEventListener('click', () => {
    modalDeleteBook.style.display = "none";
    overlayDelete.style.display = "none";
});

overlayDelete.addEventListener('click', () => {
    modalDeleteBook.style.display = "none";
    overlayDelete.style.display = "none";
});
const publisherModal = document.getElementById("publisherModel");
const closePublisherBtn = document.getElementById("closePublisherModal");


closePublisherBtn.addEventListener("click", () => {
    publisherModal.style.display = "none";
});
**/
    }
const modal2 = document.getElementById("modalAuthor");
const overlay2 = document.getElementById("overlay");
const closeBtn2 = document.querySelector(".close");

document.getElementById('tableAuthors').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-edit')) {
        const authorId2 = e.target.getAttribute('data-id');
        document.getElementById('authorId2').value = authorId2;
        console.log("Editing author ID:",  e.target.getAttribute('data-id'));
        modal2.style.display = 'block';
        overlay2.style.display = 'block';
    }
});



closeBtn2.addEventListener('click', () => {
    modal2.style.display = "none";
    overlay2.style.display = "none";
});



    
