document.querySelectorAll('.has-child .click').forEach(item => {
    item.addEventListener('click', function (e) {
        e.stopPropagation(); // không cho event lan ra ngoài

        const parentLi = this.closest('.has-child');

        parentLi.classList.toggle('open');
    });
});

// Trang save job click
document.querySelectorAll('.has-child-one .clickd').forEach(item => {
    item.addEventListener('click', function (e) {
        e.stopPropagation();

        const parentLi = this.closest('.has-child-one');
        parentLi.classList.toggle('opend');
    });
});

console.log(document.querySelectorAll('.has-child-one'));
console.log(document.querySelectorAll('.has-child-one .clickd'));


// 1. Popup thông tin cá nhân
/* ---------- biến dùng chung ---------- */
const modal = document.getElementById("profileModal");
const openBtns = document.querySelectorAll(".open-modal");
const saveBtn = document.getElementById("saveProfile");
const displayAddressEl = document.getElementById("display-address");

let provincesLoaded = false;
let pendingSetProvince = null; // dùng để set province sau khi load xong
let pendingSetDistrict = null;

/* ---------- load provinces ---------- */
fetch("https://provinces.open-api.vn/api/p/")
  .then(res => res.json())
  .then(data => {
    const selectTinh = document.getElementById("tinh");
    // giữ option mặc định
    selectTinh.innerHTML = '<option value="">Chọn tỉnh thành</option>';
    data.forEach(item => {
      const o = document.createElement("option");
      o.value = item.code;        // mã
      o.textContent = item.name;  // tên
      selectTinh.appendChild(o);
    });
    provincesLoaded = true;

    // nếu có pending (người dùng đã có tỉnh lưu trong session)
    if (pendingSetProvince) {
      selectTinh.value = pendingSetProvince;
      // tự trigger change để load huyện
      const evt = new Event('change');
      selectTinh.dispatchEvent(evt);
    }
  })
  .catch(err => console.error("Load provinces failed:", err));

/* ---------- load districts on change ---------- */
document.getElementById("tinh").addEventListener("change", function () {
  const provinceID = this.value;
  const selectHuyen = document.getElementById("huyen");
  selectHuyen.innerHTML = '<option value="">Chọn quận huyện</option>';

  if (!provinceID) return;

  fetch(`https://provinces.open-api.vn/api/p/${provinceID}?depth=2`)
    .then(res => res.json())
    .then(data => {
      data.districts.forEach(item => {
        const o = document.createElement("option");
        o.value = item.code;
        o.textContent = item.name;
        selectHuyen.appendChild(o);
      });

      // nếu có pending district (đang chờ set từ session), set nó
      if (pendingSetDistrict) {
        selectHuyen.value = pendingSetDistrict;
        pendingSetDistrict = null;
      }
    })
    .catch(err => console.error("Load districts failed:", err));
});

/* ---------- open modal: load form values ---------- */
openBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    fillProfileForm();
    modal.style.display = "flex";
  });
});

/* ---------- fill popup form from session (safe) ---------- */
function fillProfileForm() {
  const user = JSON.parse(sessionStorage.getItem("user") || "{}");
  if (!user) return;

  document.getElementById("input-name").value = user.name || "";
  document.getElementById("input-email").value = user.email || "";
  document.getElementById("input-phone").value = user.phone || "";
  document.getElementById("input-birthday").value = user.birthday || "";

  // giới tính / hôn nhân - set class active nếu trùng
  document.querySelectorAll(".gender-group .select-btn").forEach(btn => {
    btn.classList.toggle("active", btn.dataset.value === user.gender);
  });
  document.querySelectorAll(".marry-group .select-btn").forEach(btn => {
    btn.classList.toggle("active", btn.dataset.value === user.marriage);
  });

  // Tỉnh/huyện: nếu provinces chưa load xong thì lưu pending
  if (user.tinh_code) {
    if (provincesLoaded) {
      document.getElementById("tinh").value = user.tinh_code;
      // trigger change để load huyện
      const evt = new Event('change');
      document.getElementById("tinh").dispatchEvent(evt);
      // sau khi tỉnh load xong, set huyện (pending)
      pendingSetDistrict = user.huyen_code || null;
    } else {
      pendingSetProvince = user.tinh_code;
      pendingSetDistrict = user.huyen_code || null;
    }
  } else {
    // nếu không có data thì reset selects
    document.getElementById("tinh").value = "";
    document.getElementById("huyen").innerHTML = '<option value="">Chọn quận huyện</option>';
  }
}

/* ---------- single save handler: lấy cả code + name, lưu session và cập nhật UI ---------- */
saveBtn.addEventListener("click", function () {
  const tinhSelect = document.getElementById("tinh");
  const huyenSelect = document.getElementById("huyen");

  const tinh_code = tinhSelect.value || "";
  const huyen_code = huyenSelect.value || "";

  const tinh_name = (tinhSelect.options[tinhSelect.selectedIndex] || {}).textContent || "";
  const huyen_name = (huyenSelect.options[huyenSelect.selectedIndex] || {}).textContent || "";

  // cập nhật session user (lưu cả code và name)
  const user = JSON.parse(sessionStorage.getItem("user") || "{}");
  user.name = document.getElementById("input-name").value || user.name || "";
  user.email = document.getElementById("input-email").value || user.email || "";
  user.phone = document.getElementById("input-phone").value || user.phone || "";
  user.birthday = document.getElementById("input-birthday").value || user.birthday || "";

  user.gender = (document.querySelector(".gender-group .select-btn.active") || {}).dataset?.value || "";
  user.marriage = (document.querySelector(".marry-group .select-btn.active") || {}).dataset?.value || "";

  // lưu cả code + name cho tỉnh/huyện
  user.tinh_code = tinh_code;
  user.tinh_name = tinh_name;
  user.huyen_code = huyen_code;
  user.huyen_name = huyen_name;

  sessionStorage.setItem("user", JSON.stringify(user));

  // cập nhật giao diện ngoài
  updateProfileDisplay();
  document.querySelectorAll(".gender-group .select-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".gender-group .select-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
  });
});
document.querySelectorAll(".marry-group .select-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".marry-group .select-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
  });
});
document.getElementById("closeProfile").addEventListener("click", () => {
  modal.style.display = "none";
});

document.getElementById("cancelProfile").addEventListener("click", () => {
  modal.style.display = "none";
});

// Click ra ngoài modal → đóng
window.addEventListener("click", (e) => {
  if (e.target === modal) modal.style.display = "none";
});
  
  // đóng modal
  modal.style.display = "none";
  /* ---------- chọn giới tính ---------- */
document.querySelectorAll(".gender-group .select-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".gender-group .select-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
  });
});

/* ---------- chọn tình trạng hôn nhân ---------- */
document.querySelectorAll(".marry-group .select-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".marry-group .select-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
  });
});

/* ---------- nút Hủy ---------- */
document.getElementById("cancelProfile").addEventListener("click", () => {
  modal.style.display = "none";
});

/* ---------- nút X ---------- */
document.getElementById("closeProfile").addEventListener("click", () => {
  modal.style.display = "none";
});

/* ---------- click ra ngoài modal ---------- */
window.addEventListener("click", (e) => {
  if (e.target === modal) modal.style.display = "none";
});

});

/* ---------- cập nhật UI ngoài từ session (dùng *_name để hiển thị) ---------- */
function updateProfileDisplay() {
  const user = JSON.parse(sessionStorage.getItem("user") || "{}");
  if (!user) return;

  if (user.name) document.getElementById("display-name").textContent = user.name;
  if (user.email) document.getElementById("display-email").textContent = user.email;
  if (user.phone) document.getElementById("display-phone").textContent = user.phone;
  if (user.gender) document.getElementById("display-gender").textContent = user.gender;
  if (user.birthday) document.getElementById("display-birthday").textContent = user.birthday;
  if (user.marriage) document.getElementById("display-marriage").textContent = user.marriage;

  // địa chỉ: ưu tiên hiển tên; nếu tên rỗng thì để thông báo
  const tinhName = user.tinh_name || "";
  const huyenName = user.huyen_name || "";
  const addr = [huyenName, tinhName].filter(Boolean).join(", ");
  document.getElementById("display-address").textContent = addr || "Thêm địa chỉ hiện tại";
  // bỏ class màu nếu có
  if (addr) document.getElementById("display-address").classList.remove("color");
}

/* ---------- khi load trang, cập nhật UI từ session nếu có ---------- */
updateProfileDisplay();

//Popup thông tin cá nhân kết thức (1);


//2. logic xử lý file:
const uploadBtn = document.getElementById("uploadBtn");
const cvInput = document.getElementById("cvInput");
const cvFileName = document.getElementById("cvFileName");

uploadBtn.addEventListener("click", () => {
    cvInput.click();
});

// Khi người dùng chọn file
cvInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;

    // kiểm tra dung lượng
    if (file.size > 5 * 1024 * 1024) {
        alert("File quá lớn, tối đa 5MB");
        this.value = "";
        return;
    }

    // kiểm tra định dạng
    const allowed = ["application/pdf",
                     "application/msword",
                     "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];

    if (!allowed.includes(file.type)) {
        alert("Sai định dạng! Chỉ nhận pdf, doc, docx.");
        this.value = "";
        return;
    }

    // Hiển thị tên file
    cvFileName.textContent = "Đã chọn: " + file.name;

    // --- Gửi lên server PHP ---
    const formData = new FormData();
    formData.append("cvFile", file);

    fetch("https://your-backend-domain.com/api/upload_cv.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        console.log("Server trả về:", data);
        alert("Tải CV thành công!");
    })
    .catch(err => {
        console.error(err);
        alert("Lỗi kết nối server!");
    });
});



//3. POPUP 1 – TIÊU CHÍ TÌM VIỆC


document.addEventListener("DOMContentLoaded", () => {

    const popupTC = document.getElementById("popup-tieuchi");

    // Mở popup tiêu chí
    document.querySelectorAll(".item-3 .open-popup").forEach(btn => {
        btn.addEventListener("click", () => {
            popupTC.style.display = "flex";
        });
    });

    // Đóng popup
    document.getElementById("tieuchi-close-x").onclick = () => popupTC.style.display = "none";
    document.getElementById("tieuchi-cancel").onclick   = () => popupTC.style.display = "none";

    // Lưu popup tiêu chí
    document.getElementById("tieuchi-save").onclick = () => {

        let vitri     = document.getElementById("tc-vitri").value;
        let nganh     = document.getElementById("tc-nganh").value;
        let diadiem   = document.getElementById("tc-diadiem").value;
        let luongMin  = document.getElementById("tc-luong-min").value;
        let luongMax  = document.getElementById("tc-luong-max").value;
        let hinhthuc  = document.getElementById("tc-hinhthuc").value;

        document.querySelector(".flex-1 .blue-content").innerText = vitri     || "Thêm vị trí công việc";
        document.querySelector(".flex-2 .blue-content").innerText = nganh     || "Thêm ngành nghề";
        document.querySelector(".flex-3 .blue-content").innerText = diadiem   || "Thêm địa điểm";

        let luongText = (luongMin || luongMax) ?
            `${luongMin || 0} - ${luongMax || 0} triệu` :
            "Thêm mức lương";

        document.querySelector(".flex-4 .blue-content").innerText = luongText;
        document.querySelector(".flex-5 .blue-content").innerText = hinhthuc || "Thêm hình thức làm việc";

        popupTC.style.display = "none";
    };

});






//.4 POPUP 2 – THÔNG TIN CHUNG
 

    const popup2 = document.getElementById("ttc-overlay");

    // Nút mở popup TTC (dùng class mới)
    document.querySelectorAll(".ttc-open").forEach(item => {
        item.addEventListener("click", () => {
            // Đóng popup1 trước
            document.getElementById("popup").style.display = "none";

            popup2.style.display = "flex";
        });
    });

    // Đóng popup 2
    document.getElementById("ttc-close-x").addEventListener("click", () => {
        popup2.style.display = "none";
    });
    document.getElementById("ttc-btn-cancel").addEventListener("click", () => {
        popup2.style.display = "none";
    });

    // Toggle số năm kinh nghiệm
    document.querySelectorAll("input[name='ttc-exp']").forEach(radio => {
        radio.addEventListener("change", () => {
            document.getElementById("ttc-sonam-wrap").classList.toggle(
                "ttc-hide",
                radio.value === "none"
            );
        });
    });

    // Lưu popup 2
    document.getElementById("ttc-btn-save").addEventListener("click", () => {

        let exp = document.querySelector("input[name='ttc-exp']:checked").value;
        let soNam = document.getElementById("ttc-sonam").value;
        let capbac = document.getElementById("ttc-capbac").value;
        let hocvan = document.getElementById("ttc-hocvan").value;

        document.getElementById("text-kinhnghiem").innerText =
            exp === "none" ? "Chưa có kinh nghiệm" : (soNam || "Thêm số năm kinh nghiệm");

        document.getElementById("text-capbac").innerText =
            capbac || "Thêm cấp bậc hiện tại";

        document.getElementById("text-hocvan").innerText =
            hocvan || "Thêm trình độ học vấn";

        popup2.style.display = "none";
    });

// Popup Tiêu chí (item-3)
const popupTC = document.getElementById("popup-tieuchi");
const openTC = document.querySelectorAll(".item-3 .open-popup");
const closeTC = document.getElementById("tieuchi-close-x");

openTC.forEach(el => {
    el.onclick = () => popupTC.style.display = "flex";
});
closeTC.onclick = () => popupTC.style.display = "none";
/* ============================
     POPUP THÔNG TIN CHUNG
============================ */

const popupTTC = document.getElementById("ttc-overlay");

// Mở popup khi bấm chữ xanh hoặc icon bút trong item 4
document.querySelectorAll(".item-4 .blue-content, .item-4 .icon").forEach(btn => {
    btn.addEventListener("click", () => {
        // Đóng popup tiêu chí tránh trùng
        document.getElementById("popup-tieuchi").style.display = "none";

        popupTTC.style.display = "flex";
    });
});

// Đóng popup
document.getElementById("ttc-close-x").onclick = () => popupTTC.style.display = "none";
document.getElementById("ttc-btn-cancel").onclick = () => popupTTC.style.display = "none";


// Toggle số năm kinh nghiệm
document.querySelectorAll("input[name='ttc-exp']").forEach(radio => {
    radio.addEventListener("change", () => {
        document.getElementById("ttc-sonam-wrap").classList.toggle(
            "ttc-hide",
            radio.value === "none"
        );
    });
});

// Lưu popup
document.getElementById("ttc-btn-save").onclick = () => {

    let exp = document.querySelector("input[name='ttc-exp']:checked").value;
    let soNam = document.getElementById("ttc-sonam").value;
    let capbac = document.getElementById("ttc-capbac").value;
    let hocvan = document.getElementById("ttc-hocvan").value;

    document.getElementById("text-kinhnghiem").innerText =
        exp === "none" ? "Chưa có kinh nghiệm" : (soNam || "Thêm số năm kinh nghiệm");

    document.getElementById("text-capbac").innerText =
        capbac || "Thêm cấp bậc hiện tại";

    document.getElementById("text-hocvan").innerText =
        hocvan || "Thêm trình độ học vấn";

    popupTTC.style.display = "none";
};


//5. popup kinh nghiệm làm việc:
// Mở popup khi click dòng xanh hoặc icon bút
document.querySelectorAll(".open-work-exp-popup").forEach(el => {
    el.addEventListener("click", () => {
        document.getElementById("popupWorkExp").style.display = "flex";
    });
});

// Đóng popup
document.getElementById("closeWorkExp").onclick = () => {
    document.getElementById("popupWorkExp").style.display = "none";
};
document.getElementById("cancelWorkExp").onclick = () => {
    document.getElementById("popupWorkExp").style.display = "none";
};

// Nếu đang làm việc hiện tại → ẩn trường thời gian kết thúc
document.getElementById("currentJob").addEventListener("change", function () {
    document.getElementById("endTime").disabled = this.checked;
});

// Lưu dữ liệu ra màn hình
document.getElementById("saveWorkExp").onclick = function () {
    let company = document.getElementById("company").value;
    let position = document.getElementById("position").value;
    let start = document.getElementById("startTime").value;
    let end = document.getElementById("endTime").value;
    let desc = document.getElementById("description").value;
    let now = document.getElementById("currentJob").checked;

    if (!company || !position || !start || (!now && !end) || !desc) {
        alert("Vui lòng nhập đầy đủ thông tin!");
        return;
    }

    let html = `
        <strong>${position}</strong> - ${company}<br>
        <i>${start} - ${now ? "Hiện tại" : end}</i><br>
        <p>${desc}</p>
    `;

    document.getElementById("work-exp-display").innerHTML = html;

    // đóng popup sau khi lưu
    document.getElementById("popupWorkExp").style.display = "none";
};

//6. Popup học vấn:
/* ============================
        POPUP HỌC VẤN
============================ */
// Mở popup
document.querySelector(".item-5 .edit-btn").addEventListener("click", () => {
    document.getElementById("modalEdu").style.display = "flex";
});

// Đóng popup
function closeEduPopup() {
    document.getElementById("modalEdu").style.display = "none";
}

document.getElementById("edu-btn-cancel").onclick = closeEduPopup;

// Lưu dữ liệu
document.getElementById("edu-btn-save").onclick = () => {

    let truong  = document.getElementById("edu-truong").value;
    let start   = document.getElementById("edu-start").value;
    let end     = document.getElementById("edu-end").value;
    let nganh   = document.getElementById("edu-nganh").value;
    let bangcap = document.getElementById("edu-bangcap").value;
    let mota    = document.getElementById("edu-mota").value;

    // Gắn vào item-5
    const text = document.getElementById("hocvan-text");

    text.innerHTML = `
        <strong>${truong || "Chưa nhập trường"}</strong><br>
        ${start || "?"} - ${end || "?"}<br>
        ${nganh || "Chưa nhập chuyên ngành"} – ${bangcap || "Không có bằng cấp"}<br>
        <em>${mota || "Chưa có mô tả"}</em>
    `;

    closeEduPopup();
};

//7. Popup kĩ năng:

document.addEventListener("DOMContentLoaded", () => {

  // elements
  const overlay = document.getElementById("skill-overlay");
  const openBtns = document.querySelectorAll(".skill-edit-btn, .item-6 .icon, .item-6 .edit-btn");
  const closeX = document.getElementById("skill-close-x");
  const cancelBtn = document.getElementById("skill-cancel");
  const saveBtn = document.getElementById("skill-save");
  const addBtn = document.getElementById("skill-add-btn");
  const input = document.getElementById("skill-input");
  const chipsWrap = document.getElementById("skill-chips");
  const errorBox = document.getElementById("skill-error");
  const counter = document.getElementById("skill-counter");
  const targetContent = document.getElementById("skill-content");

  if (!overlay) return; // nothing to do

  // open (supports the new .skill-edit-btn or legacy icon)
  openBtns.forEach(b => {
    if (!b) return;
    b.addEventListener("click", () => {
      // close other popups if any (safeguard)
      const other = document.querySelectorAll(".popup-overlay, .ttc-overlay, #popup, #modalEdu, #popup-tieuchi");
      other.forEach(o => { if (o && o !== overlay) o.style.display = "none"; });

      overlay.style.display = "flex";
      input.focus();
    });
  });

  // close handlers
  const closeOverlay = () => {
    overlay.style.display = "none";
    // reset errors (but keep chips so user can reopen)
    errorBox.style.display = "none";
  };
  if (closeX) closeX.addEventListener("click", closeOverlay);
  if (cancelBtn) cancelBtn.addEventListener("click", closeOverlay);

  // skill storage (in-memory)
  const skills = [];

  // helper to render chips
  function renderChips() {
    chipsWrap.innerHTML = "";
    skills.forEach((s, idx) => {
      const chip = document.createElement("div");
      chip.className = "skill-chip";
      chip.innerHTML = `${escapeHtml(s)} <button class="chip-remove" data-idx="${idx}" aria-label="Xóa">&times;</button>`;
      chipsWrap.appendChild(chip);
    });

    // attach remove handlers
    chipsWrap.querySelectorAll(".chip-remove").forEach(btn => {
      btn.addEventListener("click", (e) => {
        const i = Number(btn.getAttribute("data-idx"));
        if (!Number.isNaN(i)) {
          skills.splice(i,1);
          renderChips();
        }
      });
    });
  }

  // Add skill handler
  function addSkillFromInput() {
    const val = input.value.trim();
    if (!val) return;
    if (val.length > 20) {
      errorBox.textContent = "Kỹ năng không quá 20 ký tự.";
      errorBox.style.display = "block";
      return;
    }
    // prevent duplicates (case-insensitive)
    if (skills.some(s => s.toLowerCase() === val.toLowerCase())) {
      errorBox.textContent = "Kỹ năng đã tồn tại.";
      errorBox.style.display = "block";
      input.value = "";
      return;
    }
    skills.push(val);
    input.value = "";
    errorBox.style.display = "none";
    renderChips();
    updateCounter();
  }

  if (addBtn) addBtn.addEventListener("click", addSkillFromInput);
  // allow Enter to add
  input.addEventListener("keydown", (e) => {
    updateCounter();
    if (e.key === "Enter") {
      e.preventDefault();
      addSkillFromInput();
    }
  });
  // live char counter
  function updateCounter() {
    const len = input.value.length;
    counter.innerText = `${len}/20`;
  }
  updateCounter();

  // Save -> push to page
  if (saveBtn) saveBtn.addEventListener("click", () => {
    if (skills.length === 0) {
      errorBox.textContent = "Vui lòng chọn ít nhất 1 kỹ năng.";
      errorBox.style.display = "block";
      return;
    }

    // Render into the item-6 content area (skill-content)
    targetContent.innerHTML = skills.map(s => `<span class="skill-chip" style="margin-right:8px;">${escapeHtml(s)}</span>`).join("");

    // close overlay
    closeOverlay();
  });

  // simple HTML escape
  function escapeHtml(str) {
    return str.replace(/[&<>"']/g, (m) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }

});







