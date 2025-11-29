document.addEventListener('DOMContentLoaded', function () {
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
        roleFromStorage = 'student';
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
                labels: ['Category1', 'Category2', 'Category3', 'Category4', 'Category5'],
                datasets: [{
                    label: 'Books',
                    data: [10, 7, 13, 5, 9]
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
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Loans',
                    data: [5, 8, 12, 7, 10, 6]
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

    if (statTotalBooks) statTotalBooks.textContent = '30';
    if (statAvailableBooks) statAvailableBooks.textContent = '20';
    if (statBorrowers) statBorrowers.textContent = '30';
    if (statActiveLoans) statActiveLoans.textContent = '15';

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

    function attachFormHandler(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const dataObj = {};
            formData.forEach((value, key) => {
                dataObj[key] = value;
            });

            console.log('Form submitted:', formId);
            console.log('Data:', dataObj);

            alert('Demo only: form "' + formId + '" submitted (check console).');
        });
    }

    attachFormHandler('formBookInsert');
    attachFormHandler('formAuthorInsert');
    attachFormHandler('formPublisherInsert');
    attachFormHandler('formBorrowerInsert');
    attachFormHandler('formLoanInsert');
    attachFormHandler('formSaleInsert');

    const tableBooks = document.getElementById('tableBooks');
    if (tableBooks) {
        const tbody = tableBooks.querySelector('tbody');
        if (tbody) {
            const demoBooks = [
                { id: 1, title: 'Book Title 1', publisher: 'Dar Al-Hikma', category: 'Category2', type: 'Type2', price: 13.00, available: 0 },
                { id: 11, title: 'Book Title 11', publisher: 'Al-Quds Press', category: 'Category2', type: 'Type3', price: 43.00, available: 1 },
                { id: 20, title: 'Book Title 20', publisher: 'Dar Al-Hikma', category: 'Category1', type: 'Type3', price: 70.00, available: 1 }
            ];

            tbody.innerHTML = '';
            demoBooks.forEach(book => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${book.id}</td>
                    <td>${book.title}</td>
                    <td>${book.publisher}</td>
                    <td>${book.category}</td>
                    <td>${book.type}</td>
                    <td>${book.price.toFixed(2)}</td>
                    <td>${book.available ? 'Yes' : 'No'}</td>
                    <td class="admin-only">
                        <button class="btn btn-sm btn-warning me-1 btn-edit">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }
    }

    const tableAuthors = document.getElementById('tableAuthors');
    if (tableAuthors) {
        const tbody = tableAuthors.querySelector('tbody');
        if (tbody) {
            const demoAuthors = [
                { id: 1, first: 'Omar', last: 'Khaled', country: 'Egypt', bio: 'Specialist in classical Arabic literature.' },
                { id: 3, first: 'Ahmed', last: 'Mansour', country: 'Palestine', bio: 'Researcher in Middle Eastern history.' },
                { id: 5, first: 'George', last: 'Smith', country: 'USA', bio: 'Author of science fiction novels.' }
            ];

            tbody.innerHTML = '';
            demoAuthors.forEach(a => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${a.id}</td>
                    <td>${a.first}</td>
                    <td>${a.last}</td>
                    <td>${a.country}</td>
                    <td>${a.bio}</td>
                    <td class="admin-only">
                        <button class="btn btn-sm btn-warning me-1 btn-edit">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }
    }
});
