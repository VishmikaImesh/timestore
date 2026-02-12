document.getElementById("customers-tab").addEventListener("click", loadUsers);
document.getElementById("userStatusFilter").addEventListener("change", loadUsers);

function loadUsers() {

    var status = document.getElementById("userStatusFilter").value;

    var form = new FormData();
    form.append("status", status);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response);
            var jsonObject = JSON.parse(request.response);

            document.getElementById("totalUserCount").innerHTML = jsonObject.users.length;
            document.getElementById("activeUserCount").innerHTML = jsonObject.active;
            document.getElementById("blockedUserCount").innerHTML = jsonObject.blocked;

            var customerTableBody = document.getElementById("customerTableBody");
            customerTableBody.innerHTML = "";

            var fragment = document.createDocumentFragment();

            jsonObject.users.forEach(user => {
                var tr = document.createElement("tr");
                var statusBadgeClass = "bg-success bg-opacity-10 text-success";

                if (user.status === "Blocked") {
                    statusBadgeClass = "bg-success bg-opacity-10 text-danger";
                }

                tr.innerHTML = `
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-circle me-3"></div>
                                                                <div>
                                                                    <h6 class="fw-bold text-dark mb-0">${user.first_name + " " + user.last_name}</h6>
                                                                    <small class="text-muted">ID: #USR-<?php echo rand(100, 999); ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <small class="text-dark fw-bold mb-1"><i class="bi bi-envelope me-1 text-secondary"></i> ${user.email} </small>
                                                                <small class="text-secondary"><i class="bi bi-phone me-1"></i> ${user.mobile} </small>
                                                            </div>
                                                        </td>
                                                        
                                                        <td> 
                                                            <div class="d-flex flex-column">
                                                                <small class="text-dark fw-bold">${user.order_count} Orders</small>
                                                                <small class="text-success fw-bold">LKR ${new Intl.NumberFormat('en-LK').format(user.total_spent || 0)}</small>
                                                            </div>
                                                        </td>
                                                        <td class="text-secondary small fw-bold">${user.joined_date}</td>
                                                        <td class="text-center">
                                                            <span class="badge ${statusBadgeClass} rounded-pill px-3">${user.status}</span>
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="modal" data-email=${user.email} data-bs-target="#userModal" title="View Details">
                                                                <i class="bi bi-eye-fill text-dark"></i>
                                                            </button>
                                                            
                                                                <button class="btn btn-sm btn-light border text-danger" title="Block User">
                                                                    <i class="bi bi-slash-circle"></i>
                                                                </button>
                                                           
                                                                <button class="btn btn-sm btn-light border text-success" title="Unblock User">
                                                                    <i class="bi bi-check-circle"></i>
                                                                </button>
                                                            
                                                        </td>
                                                    `;
                fragment.appendChild(tr);

            });

            customerTableBody.appendChild(fragment);
        }
    }
    request.open("POST", "/timestore/api/user/load", true);
    request.send(form);

}