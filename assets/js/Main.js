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

   
 
   



    const logoutBtn = document.getElementById('btn-logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
                window.location.href = '../views/login.php';
                session_destroy();
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
 console.log(role)
            tbody.innerHTML = '';
            demoBooks.forEach(book => {
                const tr = document.createElement('tr');

                if(role==="admin"){
                    tr.innerHTML= `
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
                }
                else{
                tr.innerHTML = `
                    <td>${book.book_id}</td>
                    <td>${book.title}</td>
                    <td>${book.publisher_id}</td>
                    <td>${book.category}</td>
                    <td>${book.book_type}</td>
                    <td>${book.original_price}</td>
                    <td>${book.available >0?'Yes' : 'No'}</td>
                `;}
                tbody.appendChild(tr);
            });
        }}
        let str = '';
        for(let publisher of allPublisher) {
        
            str += `<option value="${publisher.publisher_id}">${publisher.name}</option>`;
        }
        let selectPublisher = document.getElementById('publisherSelect');
        selectPublisher.innerHTML = `<select class="form-select" name="publisher_id" id="publisherSelect">` + 
                                        `<option value="">Select Publisher</option>` + str + `</select>`;});
let selectborrowerType = document.getElementById('borrowerTypeSelect');
        let str2 = '';
        for(let borrowerType of allBorrowersTypes) {
          
            str2 += `<option value="${borrowerType.type_id}">${borrowerType.type_name}</option>`;
        }
        selectborrowerType.innerHTML = `<select class="form-select" name="type_id" id="borrowerTypeSelect">` +
                                        `<option value="">Select Borrower Type</option>` + str2 + `</select>`;
let selectLoanBorrower = document.getElementById('LoanBorrowerTypeSelect');
        let str3 = '';
        for(let borrower of allBorrowers) {
          
            str3 += `<option value="${borrower.borrower_id}">${borrower.first_name} ${borrower.last_name}</option>`;
        }
        selectLoanBorrower.innerHTML = `<select class="form-select" name="borrower_id" id="LoanBorrowerTypeSelect">` +
                                        `<option value="">Select Borrower</option>` + str3 + `</select>`;
let selectLoanBook = document.getElementById('LoanBookTypeSelect');
        let str4 = '';
        for(let book of allBooks) {
           
            str4 += `<option value="${book.book_id}">${book.title}</option>`;
        }
        selectLoanBook.innerHTML = `<select class="form-select" name="book_id" id="LoanBookTypeSelect">` +
                                        `<option value="">Select Book</option>` + str4 + `</select>`;
let selectLoanPeriod = document.getElementById('LoanPeriodTypeSelect');
        let str5 = '';
        for(let period of allLoanPeriods) {
            
            str5 += `<option value="${period.period_id}">${period.period_name}</option>`;
        }
        selectLoanPeriod.innerHTML = `<select class="form-select" name="period_id" id="LoanPeriodTypeSelect">` +
                                        `<option value="">Select Loan Period</option>` + str5 + `</select>`;
let selectSaleBook = document.getElementById('SaleBookList');
        let str6 = '';
        for(let book of allNotsoldBooks) {
            
            str6 += `<option value="${book.book_id}">${book.title}</option>`;
        }
        selectSaleBook.innerHTML = `<select class="form-select" name="book_id" id="SaleBookList">` +
                                        `<option value="">Select Book</option>` + str6 + `</select>`;
let selectSaleBorrower = document.getElementById('SaleBorrowerList');
        let str7 = '';
        for(let borrower of allBorrowers) {
        
            str7 += `<option value="${borrower.borrower_id}">${borrower.first_name} ${borrower.last_name}</option>`;
        }   
        selectSaleBorrower.innerHTML = `<select class="form-select" name="borrower_id" id="SaleBorrowerList">` +
                                        `<option value="">Select Borrower</option>` + str7 + `</select>`;

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
const foreignKeyModal = document.getElementById("forginkey");
const closeForeignKeyBtn = document.getElementById("closePforginkey");

closeForeignKeyBtn.addEventListener("click", () => {
    foreignKeyModal.style.display = "none";
});



    const tableAuthors = document.getElementById('tableAuthors');
    if (tableAuthors) {
        const tbody = tableAuthors.querySelector('tbody');
        if (tbody) {
            const demoAuthors = allAuthors;

            tbody.innerHTML = '';
            demoAuthors.forEach(a => {
                const tr = document.createElement('tr');
                if(role==='admin'){
                    tr.innerHTML=`
                      <td><span class="author_id">${a.author_id}</span></td>
                    <td><span class="first_name">${a.first_name}</span></td>
                    <td><span class="last_name">${a.last_name}</span></td>
                    <td><span class="country">${a.country}</span></td>
                    <td><span class="bio">${a.bio}</span></td>
                    <td class="admin-only">
                        <button class="btn btn-sm btn-warning me-1 btn-edit" data-id="${a.author_id}">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${a.author_id}">Delete</button>
                    </td>
                `
                }
                tr.innerHTML = `
                    <td><span class="author_id">${a.author_id}</span></td>
                    <td><span class="first_name">${a.first_name}</span></td>
                    <td><span class="last_name">${a.last_name}</span></td>
                    <td><span class="country">${a.country}</span></td>
                    <td><span class="bio">${a.bio}</span></td>`;
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
const closeBtn2 = document.querySelector("#closeModalAuthor");

document.getElementById('tableAuthors').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-edit')) {
        const authorId = e.target.getAttribute('data-id');
        document.getElementById('authorId').value = authorId;
        console.log("Editing author ID:",  e.target.getAttribute('data-id'));
        modal2.style.display = 'block';
        overlay2.style.display = 'block';
    }
});



closeBtn2.addEventListener('click', () => {
    modal2.style.display = "none";
    overlay2.style.display = "none";
});
const overlayDelete2 = document.getElementById("overlayDelete");
const modalDeleteAuthor = document.getElementById("modalDeleteAuthor");
const closeModalDeleteBtn2 = document.getElementById("closeModalDeleteAuthor");
const btnCancelDelete2 = document.getElementById("btnCancelDeleteAuthor");

document.getElementById('tableAuthors').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-delete')) {
        const AuthorId = e.target.getAttribute('data-id');
        document.getElementById('deleteAuthorId').value = AuthorId;
        modalDeleteAuthor.style.display = 'block';
        overlayDelete2.style.display = 'block';
    }
});

closeModalDeleteBtn2.addEventListener('click', () => {
    modalDeleteAuthor.style.display = "none";
    overlayDelete2.style.display = "none";
});
btnCancelDelete2.addEventListener('click', () => {
    modalDeleteAuthor.style.display = "none";
    overlayDelete2.style.display = "none";
});

overlayDelete2.addEventListener('click', () => {
    modalDeleteAuthor.style.display = "none";
    overlayDelete2.style.display = "none";
});

const tablePublisher= document.getElementById('tablePublisher');
    if (tablePublisher) {
        const tbody = tablePublisher.querySelector('tbody');
        if (tbody) {
            const demoPublisher = allPublisher;
            tbody.innerHTML = '';
            demoPublisher.forEach(p => {
                const tr = document.createElement('tr');
                if(role==='admin'){
                    tr.innerHTML=` <td><span class="publisher_id">${p.publisher_id}</span></td>
                    <td><span class="name">${p.name}</span></td>
                    <td><span class="address">${p.address}</span></td>
                    <td><span class="phone">${p.phone}</span></td>
                    <td><span class="email">${p.email}</span></td>
                    <td class="admin-only">
                        <button class="btn btn-sm btn-warning me-1 btn-edit" data-id="${p.publisher_id}">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${p.publisher_id}">Delete</button>

                    </td>`
                }
                tr.innerHTML = `
                    <td><span class="publisher_id">${p.publisher_id}</span></td>
                    <td><span class="name">${p.name}</span></td>
                    <td><span class="address">${p.address}</span></td>
                    <td><span class="phone">${p.phone}</span></td>
                    <td><span class="email">${p.email}</span></td>
                `;
                tbody.appendChild(tr);
            });
        }
    }

const tableBorrowers= document.getElementById('tableBorrowers');
    if (tableBorrowers) {
        const tbody = tableBorrowers.querySelector('tbody');
        if (tbody) {
            const demoBorrowers = allBorrowers;
            tbody.innerHTML = '';
            demoBorrowers.forEach(b => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="borrower_id">${b.borrower_id}</span></td>
                    <td><span class="first_name">${b.first_name}</span></td>
                    <td><span class="last_name">${b.last_name}</span></td>
                    <td><span class="type_id">${b.borrowertype_id}</span></td>
                    <td><span class="contact_info">${b.contact_info}</span></td>
                `;
                tbody.appendChild(tr);
            });
        }
    }
    const tableLoans= document.getElementById('tableLoans');
    if (tableLoans) {
        const tbody = tableLoans.querySelector('tbody');
        if (tbody) {
            const demoLoans = allLoans;
            tbody.innerHTML = '';
            demoLoans.forEach(l => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="loan_id">${l.loan_id}</span></td>
                    <td><span class="borrower_id">${l.borrower_id}</span></td>
                    <td><span class="book_id">${l.book_id}</span></td>
                    <td><span class="period_id">${l.loanperiod_id}</span></td>
                    <td><span class="loan_date">${l.loan_date}</span></td>
                    <td><span class="due_date">${l.due_date}</span></td>
                    <td><span class="return_date">${l.return_date ===null ? "Not yet" :l.return_date }</span></td>
                `;
                tbody.appendChild(tr);
            });
        }
    }
    const tableSales= document.getElementById('tableSales');
    if (tableSales) {
        const tbody = tableSales.querySelector('tbody');
        if (tbody) {
            const demoSales = allSales;
            tbody.innerHTML = '';
            demoSales.forEach(s => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="sale_id">${s.sale_id}</span></td>
                    <td><span class="borrower_id">${s.borrower_id}</span></td>
                    <td><span class="book_id">${s.book_id}</span></td>
                    <td><span class="sale_date">${s.sale_date}</span></td>
                    <td><span class="sale_price">${s.sale_price}</span></td>
                `;
                tbody.appendChild(tr);
            });
        }
    }
    const tablePublishers= document.getElementById('tablePublishers');
    if (tablePublishers) {
        const tbody = tablePublishers.querySelector('tbody');
        if (tbody) {
            const demoPublishers = allPublisher;
            tbody.innerHTML = '';
            demoPublishers.forEach(p => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="publisher_id">${p.publisher_id}</span></td>
                    <td><span class="name">${p.name}</span></td>
                    <td><span class="city">${p.city}</span></td>
                    <td><span class="country">${p.country}</span></td>
                    <td><span class="contact_info">${p.contact_info}</span></td>
                    <td class="admin-only">
                        <button class="btn btn-sm btn-warning me-1 btn-edit" data-id="${p.publisher_id}">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${p.publisher_id}">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }
    }
    // const modal = document.getElementById("modalBook");
    // const overlay = document.getElementById("overlay");
    // const closeBtn = document.querySelector(".close");
    
    // document.getElementById('tableBooks').addEventListener('click', (e) => {
    //     if (e.target && e.target.classList.contains('btn-edit')) {
    //         const bookId = e.target.getAttribute('data-id');
    //         document.getElementById('bookId').value = bookId;
    //         console.log("Editing book ID:",  e.target.getAttribute('data-id'));
    //         modal.style.display = 'block';
    //         overlay.style.display = 'block';
    //     }
    // });
    
    
    
    // closeBtn.addEventListener('click', () => {
    //     modal.style.display = "none";
    //     overlay.style.display = "none";
    // });
    
    // overlay.addEventListener('click', () => {
    //     modal.style.display = "none";
    //     overlay.style.display = "none";
    // });
    // const overlayDelete = document.getElementById("overlayDelete");
    // const modalDeleteBook = document.getElementById("modalDeleteBook");
    // const closeModalDeleteBtn = document.getElementById("closeModalDeleteBook");
    // const btnCancelDelete = document.getElementById("btnCancelDelete");
    
    // document.getElementById('tableBooks').addEventListener('click', (e) => {
    //     if (e.target && e.target.classList.contains('btn-delete')) {
    //         const bookId = e.target.getAttribute('data-id');
    //         document.getElementById('deleteBookId').value = bookId;
    //         modalDeleteBook.style.display = 'block';
    //         overlayDelete.style.display = 'block';
    //     }
    // });
    
    // closeModalDeleteBtn.addEventListener('click', () => {
    //     modalDeleteBook.style.display = "none";
    //     overlayDelete.style.display = "none";
    // });
    // btnCancelDelete.addEventListener('click', () => {
    //     modalDeleteBook.style.display = "none";
    //     overlayDelete.style.display = "none";
    // });
    
    // overlayDelete.addEventListener('click', () => {
    //     modalDeleteBook.style.display = "none";
    //     overlayDelete.style.display = "none";
    // });
const modelPublisher= document.getElementById("modalPublisher");
const overlayPublisher= document.getElementById("overlayPublisher");
const closeModalPublisher= document.getElementById("closeModalPublisher");
document.getElementById('tablePublishers').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-edit')) {
        const publisherId = e.target.getAttribute('data-id');
        document.getElementById('publisherId').value = publisherId;
        console.log("Editing publisher ID:",  e.target.getAttribute('data-id'));
        modelPublisher.style.display = 'block';
        overlayPublisher.style.display = 'block';
    }
});
closeModalPublisher.addEventListener('click', () => {
    modelPublisher.style.display = "none";
    overlayPublisher.style.display = "none";
});
overlayPublisher.addEventListener('click', () => {
    modelPublisher.style.display = "none";
    overlayPublisher.style.display = "none";
});
const modalDeletePublisher= document.getElementById("modalDeletePublisher");
const closeModalDeletePublisher= document.getElementById("closeModalDeletePublisher");
const btnCancelDeletePublisher= document.getElementById("btnCancelDeletePublisher");
document.getElementById('tablePublishers').addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('btn-delete')) {
        const publisherId = e.target.getAttribute('data-id');
        document.getElementById('deletePublisherId').value = publisherId;
        modalDeletePublisher.style.display = 'block';
        overlayDeletePublisher.style.display = 'block';
    }
});
closeModalDeletePublisher.addEventListener('click', () => {
    modalDeletePublisher.style.display = "none";
    overlayDeletePublisher.style.display = "none";
});
btnCancelDeletePublisher.addEventListener('click', () => {
    modalDeletePublisher.style.display = "none";
    overlayDeletePublisher.style.display = "none";
});
overlayDeletePublisher.addEventListener('click', () => {
    modalDeletePublisher.style.display = "none";
    overlayDeletePublisher.style.display = "none";
});