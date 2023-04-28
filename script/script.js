//------------------Connexion-------------------------//

const logButton = document.getElementById("logButton")

if (logButton) {
  logButton.addEventListener("click", function () {

    const emailAdminValue = document.getElementById("emailLogin").value
    const mdpAdminValue = document.getElementById("passwordLogin").value

    const connectData = new FormData();
    connectData.append('connect', 1);
    connectData.append('emailAdminValue', emailAdminValue);
    connectData.append('mdpAdminValue', mdpAdminValue);

    fetch('index.php?controller=login&action=connect', {
      method: 'POST',
      body: connectData
    })
      .then(response => response.json())
      .then(data => {
        if (data.connexion) {
          window.location.href = "index.php?controller=home";
        } else {
          document.getElementById("connexionStatus").style.display= "block";
        }
      })
      .catch(error => console.error(error));
  });
}


//-----------------Exporter un Groupe--------------------------//

exportGroup()

function exportGroup() {
  const buttons = document.querySelectorAll(".exportGroup");
  for (const btn of buttons) {
    btn.removeEventListener("click", exportGroupListener);
    btn.addEventListener("click", exportGroupListener);
  }
}

function exportGroupListener() {
  const btn = this;
  const groupId = btn.dataset.id;

  const exportData = new FormData();
  exportData.append('export', 1);
  exportData.append('groupId', groupId);

  fetch('index.php?controller=Group&action=export', {
    method: 'POST',
    body: exportData
  })
    .then(response => response.text())
    .then(data => {

      const blob = new Blob([data], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = 'mon_flux.csv';
      link.click();

    })
    .catch(error => console.error(error));
}

//-------------Ajouter un Groupe---------------------------//

const addGroupButtons = document.getElementById("submitGroup")

if (addGroupButtons) {
  document.getElementById("submitGroup").addEventListener("click", addGroup);

  function addGroup() {
    if (document.getElementById("formGroup").checkValidity()) {
      const groupTitle = document.getElementById("groupTitle").value;
      const groupDescription = document.getElementById("groupDescription").value;

      const addData = new FormData();
      addData.append("save3", 1);
      addData.append("groupTitle", groupTitle);
      addData.append("groupDescription", groupDescription);

      fetch("index.php?controller=Group&action=add", {
        method: "POST",
        body: addData
      })
        .then(response => response.json())
        .then(data => {
          document.getElementById("groupTitle").value = "";
          document.getElementById("groupDescription").value = "";

          const pushGroupList = data.pushGroupList;
          const pushBulletGroupList = data.pushBulletGroupList;

          document.getElementById("displayGroup").insertAdjacentHTML("beforeend", pushGroupList);
          document.getElementById("displayBulletPointGroup").insertAdjacentHTML("beforeend", pushBulletGroupList);

          deleteGroup();
          editGroup();
          updateGroup();
          exportGroup()

        })
        .catch(error => console.error(error));

    } else {
      alert("veuillez remplir tous les champs")

    }

  }
}

//-------------Supprimer un Groupe ---------------------------//


deleteGroup()

function deleteGroup() {
  const buttons = document.querySelectorAll(".deleteGroup");
  for (const btn of buttons) {
    btn.removeEventListener("click", deleteGroupListener);
    btn.addEventListener("click", deleteGroupListener);
  }
}

function deleteGroupListener() {
  const btn = this;
  const groupId = btn.dataset.id;

  const deleteData = new FormData();
  deleteData.append('delete3', 1);
  deleteData.append('groupId', groupId);

  fetch('index.php?controller=Group&action=delete', {
    method: 'POST',
    body: deleteData
  })
    .then(response => response.json())
    .then(data => {
      const groupToDelete = "pushGroup" + data.groupId;
      const groupToDeleteBulleted = "bulletedGroupList" + data.groupId
      document.getElementById(groupToDelete).remove()
      document.getElementById(groupToDeleteBulleted).remove()


    })
    .catch(error => console.error(error));
}

//-----------------------Editer un groupe-------------------//

editGroup()

let groupToEditId

function editGroup() {
  const buttons = document.querySelectorAll(".editGroup")
  for (const btn of buttons) {
    btn.addEventListener("click", function () {
      goTopPage();

      groupToEditId = btn.dataset.id;

      const divGroup = this.parentNode.parentNode;
      const groupTitle = divGroup.querySelector("h4")
      const groupDescription = divGroup.querySelector("p");

      document.getElementById('groupTitle').value = groupTitle.textContent;
      document.getElementById('groupDescription').value = groupDescription.textContent;

      document.getElementById('actionGroup').textContent = "Modifier";
      document.getElementById('submitGroup').style.display = 'none';
      document.getElementById('updateGroup').style.display = 'block';
    })
  }
}

//-----------------Update une groupe--------------------------//

updateGroup()

function updateGroup() {

  const updateGroup = document.getElementById("updateGroup")

  if (updateGroup) {
    document.getElementById('updateGroup').addEventListener("click", updateGroupListener)

    function updateGroupListener() {

      const groupId = groupToEditId;
      const groupTitle = document.getElementById('groupTitle').value;
      const groupDescription = document.getElementById('groupDescription').value;

      const updateData = new FormData();
      updateData.append("update3", 1);
      updateData.append("groupId", groupId);
      updateData.append("groupTitle", groupTitle);
      updateData.append("groupDescription", groupDescription);

      fetch('index.php?controller=Group&action=edit', {
        method: 'POST',
        body: updateData
      })
        .then(response => response.json())
        .then(data => {

          document.getElementById('actionGroup').textContent = "Ajouter";

          document.getElementById('groupTitle').value = '';
          document.getElementById('groupDescription').value = '';

          document.getElementById('submitGroup').style.display = 'block';
          document.getElementById('updateGroup').style.display = 'none';

          document.getElementById("title" + data.groupId).textContent = data.groupTitle
          document.getElementById("desc" + data.groupId).textContent = data.groupDescription
          document.getElementById("bulletedGroupList" + data.groupId).textContent = data.groupTitle

          document.getElementById("redirect" + data.groupId).href = data.groupUrl;

        });
    }
  }

}

//-------------Ajouter une category---------------------------//



const addCategoryButtons = document.getElementById("addCategory")

if (addCategoryButtons) {
  document.getElementById("addCategory").addEventListener("click", addCategory);

  function addCategory() {
    if (document.getElementById("formCategory").checkValidity()) {

      const categoryTitle = document.getElementById("categoryTitle").value;
      const categoryDescription = document.getElementById("categoryDescription").value;
      const groupId = document.getElementById("groupId").value;
      const categoryRank = document.getElementById("categoryRank").value;

      const addData = new FormData();
      addData.append("save", 1);
      addData.append("categoryTitle", categoryTitle);
      addData.append("categoryDescription", categoryDescription);
      addData.append("categoryRank", categoryRank);
      addData.append("groupId", groupId);

      fetch("index.php?controller=Category&action=add", {
        method: "POST",
        body: addData
      })
        .then(response => response.json())
        .then(data => {
          let categoryRankValue = parseInt(data.categoryRank) + 1;
          document.getElementById("categoryRank").value = categoryRankValue;
       

          document.getElementById("categoryTitle").value = "";
          document.getElementById("categoryDescription").value = "";

          const pushCategoryList = data.pushCategoryList;
          const pushBulletCategoryList = data.pushBulletCategoryList;

          document.getElementById("displayCategory").insertAdjacentHTML("beforeend", pushCategoryList);
          document.getElementById("displayBulletPointCategory").insertAdjacentHTML("beforeend", pushBulletCategoryList);

          addItem();
          deleteitem();
          editItem();
          updateItem();
          addImage()
          upRankCategory()
          downRankCategory()


        })
        .catch(error => console.error(error));
    } else {
      alert("Veuillez remplir tous les champs")

    }


  }
}


//-------------Supprimer une category ---------------------------//

document.addEventListener('click', function (event) {
  if (event.target.classList.contains('deleteCategory')) {
    const categoryId = event.target.dataset.id;

    const deleteData = new FormData();
    deleteData.append('delete', 1);
    deleteData.append('categoryId', categoryId);

    fetch('index.php?controller=Category&action=delete', {
      method: 'POST',
      body: deleteData
    })
      .then(response => response.json())
      .then(data => {
        let categoryRankValue = parseInt(document.getElementById("categoryRank").value)
        categoryRankValue = categoryRankValue - 1;
        document.getElementById("categoryRank").value = categoryRankValue
        

        // Udpate les rank suivants en retirant 1 à chacun d'entre eux
        const selectRank = document.getElementById("inputRank" + data.categoryId).value
        const allRank = document.querySelectorAll(".inputRank")
        for (i=0 ; i < allRank.length ; i++){
          if (allRank[i].value>selectRank){
            allRank[i].value = allRank[i].value - 1;

          }
        } 

        const categoryblocToDelete = "pushCategory" + data.categoryId;
        const categoryBulletToDelete = "category" + data.categoryId;
        document.getElementById(categoryblocToDelete).remove();
        document.getElementById(categoryBulletToDelete).remove()
        document.getElementById('categoryTitle').value = '';
        document.getElementById('categoryDescription').value = '';
      })
      .catch(error => console.error(error));
  }
});



//-------------Editer une Category ---------------------------//


let categoryToEditId;

document.addEventListener('click', function (event) {

  if (event.target.classList.contains('edit')) {
    goTopPage();
    categoryToEditId = event.target.dataset.id;

    const divCategory = document.getElementById("pushCategory" + categoryToEditId);
    const categoryTitle = divCategory.querySelector("h4")
    const categoryDescription = divCategory.querySelector("p")

    console.log(divCategory)

    document.getElementById('categoryTitle').value = categoryTitle.textContent;
    document.getElementById('categoryDescription').value = categoryDescription.textContent;
    document.getElementById('actionCategory').textContent = "Modifier";

    document.getElementById('addCategory').style.display = 'none';
    document.getElementById('updateCategory').style.display = 'block';
  }
});

function goTopPage() {
  window.scrollTo(0, 0);
}




//-------------Update une Category ---------------------------//


document.addEventListener('click', function (event) {
  if (event.target.id === 'updateCategory') {

    const categoryId = categoryToEditId;

    const categoryTitle = document.getElementById('categoryTitle').value;
    const categoryDescription = document.getElementById('categoryDescription').value;

    const updateData = new FormData();
    updateData.append("update", 1);
    updateData.append("categoryTitle", categoryTitle);
    updateData.append("categoryDescription", categoryDescription);
    updateData.append("categoryId", categoryId);

    fetch('index.php?controller=Category&action=edit', {
      method: 'POST',
      body: updateData
    })
      .then(response => response.json())
      .then(data => {

        document.getElementById('actionCategory').textContent = "Ajouter";

        document.getElementById('categoryTitle').value = '';
        document.getElementById('categoryDescription').value = '';

        document.getElementById('addCategory').style.display = 'block';
        document.getElementById('updateCategory').style.display = 'none';

        document.getElementById("title" + data.categoryId).textContent = data.categoryTitle
        document.getElementById("desc" + data.categoryId).textContent = data.categoryDescription
        document.getElementById("category" + data.categoryId).textContent = data.categoryTitle

      });
  }
});

//---------------Organiser une category------------------//

upRankCategory()

function upRankCategory() {
  const buttons = document.querySelectorAll(".downRank");
  for (const btn of buttons) {
    btn.removeEventListener("click", setRankCategoryListener);
    btn.addEventListener("click", () => setRankCategoryListener(btn, "decrement"));
  }
}

downRankCategory()

function downRankCategory() {
  const buttons = document.querySelectorAll(".upRank");
  for (const btn of buttons) {
    btn.removeEventListener("click", setRankCategoryListener);
    btn.addEventListener("click", () => setRankCategoryListener(btn, "increment"));
  }
}


function setRankCategoryListener(btn, action) {

  const thisCategoryId = btn.dataset.id;
  const categoryRankInput = document.getElementById('inputRank' + thisCategoryId);
  const thisCategoryBloc = document.getElementById("pushCategory" + thisCategoryId);

  let thisCategoryRankValue = parseInt(categoryRankInput.value);

  let nextBloc;
  let nextCategoryBlocId;
  let nextCategoryRankValue;

  let prevBloc;
  let prevCategoryBlocId;
  let previousCategoryRankValue;

  if (action === "increment") {
    nextBloc = thisCategoryBloc.nextElementSibling;
    if (!nextBloc) {
      return;
    }
    nextCategoryBlocId = nextBloc.dataset.id;
    nextCategoryRankValue = parseInt(thisCategoryBloc.nextElementSibling.querySelector('.inputRank').value);
    nextCategoryRankValue--;
    thisCategoryRankValue++;
  } else if (action === "decrement") {
    prevBloc = thisCategoryBloc.previousElementSibling;
    if (!prevBloc) {
      return;
    }
    prevCategoryBlocId = prevBloc.dataset.id;
    previousCategoryRankValue = parseInt(thisCategoryBloc.previousElementSibling.querySelector('.inputRank').value);
    previousCategoryRankValue++;
    thisCategoryRankValue--;
  }
  const upRankData = new FormData();
  upRankData.append("upRank", 1);
  upRankData.append("thisCategoryRankValue", thisCategoryRankValue);
  upRankData.append("thisCategoryId", thisCategoryId);
  if (action === "increment") {
    upRankData.append("nextCategoryRankValue", nextCategoryRankValue);
    upRankData.append("nextCategoryBlocId", nextCategoryBlocId);
  } else if (action === "decrement") {
    upRankData.append("previousCategoryRankValue", previousCategoryRankValue);
    upRankData.append("prevCategoryBlocId", prevCategoryBlocId);
  }
  upRankData.append("action", action);
  fetch('index.php?controller=Category&action=editRank', {
      method: 'POST',
      body: upRankData
    })
    .then(response => response.json())
    .then(data => {
      document.getElementById('inputRank' + data.thisCategoryId).value = data.thisCategoryRankValue;
      const thisCategoryBloc = document.getElementById("pushCategory" + data.thisCategoryId);
      const thisCategoryBullet = document.getElementById("category" + data.thisCategoryId);

      const parentCategory = document.getElementById("displayCategory");
      const parentBulletCategory = document.getElementById("displayBulletPointCategory");

      if (data.action === "increment") {
        const nextCategoryBloc = thisCategoryBloc.nextElementSibling;
        const nextCategoryRankInput = nextCategoryBloc.querySelector('.inputRank');
        nextCategoryRankInput.value = parseInt(nextCategoryRankInput.value) - 1;
        parentCategory.insertBefore(nextCategoryBloc, thisCategoryBloc);

        const nextCategoryBullet = thisCategoryBullet.nextElementSibling;
        parentBulletCategory.insertBefore(nextCategoryBullet, thisCategoryBullet);

      } else if (data.action === "decrement") {
        const prevCategoryBloc = thisCategoryBloc.previousElementSibling;
        const previousCategoryRankInput = prevCategoryBloc.querySelector('.inputRank');
        previousCategoryRankInput.value = parseInt(previousCategoryRankInput.value) + 1;
        thisCategoryBloc.parentNode.insertBefore(prevCategoryBloc, thisCategoryBloc.nextSibling);

        const prevCategoryBullet = thisCategoryBullet.previousElementSibling;
        thisCategoryBullet.parentNode.insertBefore(prevCategoryBullet, thisCategoryBullet.nextSibling);

      }
    })
    .catch(error => console.error(error));
}




//---------------Ajouter une image------------------//

addImage()

function addImage() {
  const buttons = document.querySelectorAll(".inputImage");
  for (const btn of buttons) {
    btn.removeEventListener("change", addImageListener);
    btn.addEventListener("change", addImageListener);
  }
}

function addImageListener() {
  const input = this;
  const categoryIdKey = input.dataset.id;

  const itemImagePreview = document.getElementById("itemImagePreview" + categoryIdKey);
  const imgDefault = document.getElementById("imgDefault" + categoryIdKey);

  const file = input.files[0];
  const reader = new FileReader();

  reader.addEventListener('load', function () {
    itemImagePreview.setAttribute('src', reader.result);
    imgDefault.style.display = "none"
  });

  reader.readAsDataURL(file);

}





//-------------Ajouter un Item---------------------------//

addItem()

function addItem() {
  const buttons = document.querySelectorAll(".addItem");
  for (const btn of buttons) {
    btn.removeEventListener("click", addItemListener);
    btn.addEventListener("click", addItemListener);
  }
}

function addItemListener() {

  const btn = this;
  const categoryIdKey = btn.dataset.id;

  const itemTitle = document.getElementById("itemTitle" + categoryIdKey).value;
  const itemDescription = document.getElementById("itemDescription" + categoryIdKey).value;
  const itemPrice = document.getElementById("itemPrice" + categoryIdKey).value;
  const category = document.getElementById("categoryId" + categoryIdKey).value;

  const inputImageId = "inputImage" + categoryIdKey;

  const addData = new FormData();
  addData.append("save2", 1);
  addData.append("itemTitle", itemTitle);
  addData.append("itemDescription", itemDescription);
  addData.append("itemPrice", itemPrice);
  addData.append("categoryId", category);

  if (document.getElementById(inputImageId).files.length > 0) {
    const inputImage = document.getElementById(inputImageId).files[0];
    addData.append("inputImage", inputImage);
  }

  fetch("index.php?controller=Item&action=add", {
    method: "POST",
    body: addData,
  })
    .then((response) => response.json())
    .then((data) => {
      const pushItemList = data.pushItemList;
      const displayAreaId = "displayItem" + data.categoryId;
      document
        .getElementById(displayAreaId)
        .insertAdjacentHTML("beforeend", pushItemList);

      document.getElementById("itemTitle" + data.categoryId).value = "";
      document.getElementById("itemDescription" + data.categoryId).value = "";
      document.getElementById("itemPrice" + data.categoryId).value = "";

      document.getElementById("inputImage" + data.categoryId).value = "";
      document.getElementById("imgDefault" + categoryIdKey).style.display = "block";
      document.getElementById("itemImagePreview" + data.categoryId).setAttribute('src', '');

      deleteitem();
      editItem();
      updateItem();
      addImage()


    })
    .catch((error) => console.error(error));

}


//-------------Supprimer un Item ---------------------------//

deleteitem()

function deleteitem() {
  const buttons = document.querySelectorAll(".deleteItem");
  for (const btn of buttons) {
    btn.removeEventListener("click", deleteItemListener);
    btn.addEventListener("click", deleteItemListener);
  }
}

function deleteItemListener() {
  const btn = this;

  const itemId = btn.dataset.id;
  const categoryId = btn.dataset.categoryid

  const deleteData = new FormData();
  deleteData.append("delete", 1);
  deleteData.append("itemId", itemId);
  deleteData.append("categoryId", categoryId);

  fetch("index.php?controller=Item&action=delete", {
    method: "POST",
    body: deleteData,
  })
    .then((response) => response.json())
    .then((data) => {
      pushItemId = "pushItem" + data.itemId;
      document.getElementById(pushItemId).remove();

      document.getElementById("addItemButton" + data.categoryId).style.display = 'block';
      document.getElementById("updateItemButton" + data.categoryId).style.display = 'none';

      document.querySelector(".inputItemTitle" + data.categoryId).value = ""
      document.querySelector(".inputItemDescription" + data.categoryId).value = ""
      document.querySelector(".inputItemPrice" + data.categoryId).value = ""

      document.getElementById("inputImage" + data.categoryId).value = "";
      document.getElementById("imgDefault" + data.categoryId).style.display = "block";
      document.getElementById("itemImagePreview" + data.categoryId).setAttribute('src', '');

    })
    .catch((error) => console.error(error));
}


//----------------Editer un item-------------//

editItem()

let itemToEditId

function editItem() {
  let buttons = document.querySelectorAll(".editItem");
  for (const btn of buttons) {
    btn.addEventListener("click", function () {
      itemToEditId = btn.dataset.id;
      const categoryId = btn.dataset.categoryid;

      const itemTitleId = "itemTitle" + itemToEditId;
      const itemDescriptionId = "itemDescription" + itemToEditId;
      const itemPriceId = "itemPrice" + itemToEditId;

      const itemTitleElement = document.getElementById(itemTitleId);
      const itemDescriptionElement = document.getElementById(itemDescriptionId);
      const itemPriceElement = document.getElementById(itemPriceId);

      if (itemTitleElement !== null || itemDescriptionElement !== null || itemPriceElement !== null) {
        const itemTitleData = document.getElementById(itemTitleId).textContent;
        const itemDescriptionData = document.getElementById(itemDescriptionId).textContent;
        const itemPriceData = document.getElementById(itemPriceId).textContent;

        const imgToEdit = categoryId + "-" + itemToEditId + ".jpg"

        document.querySelector(".inputItemTitle" + categoryId).value = itemTitleData;
        document.querySelector(".inputItemDescription" + categoryId).value = itemDescriptionData;
        document.querySelector(".inputItemPrice" + categoryId).value = itemPriceData;

        document.getElementById("addItemButton" + categoryId).style.display = 'none';
        document.getElementById("updateItemButton" + categoryId).style.display = 'block';

        const imgPreview = document.getElementById("itemImagePreview" + categoryId);
        const imgDefault = document.getElementById("imgDefault" + categoryId);


        fetch('itemImages/' + imgToEdit, { method: 'HEAD' })
          .then(response => {
            if (response.ok) {
              const imageName = response.url.split('/').pop();
              imgDefault.style.display = "none"
              imgPreview.src = 'itemImages/' + imageName
            } else {
              imgDefault.style.display = "none"
              imgPreview.src = 'itemImages/image-no-avalaible.jpg'
              throw new Error('L\'image n\'existe pas');
            }
          })
          .catch(error => {
            console.error(error);
          });


        updateItem();


      } else {
        console.error("L'élément " + itemTitleId + " et " + itemDescriptionId + " et " + itemPriceId + " n'existe pas.");
      }
    })
  }
}

//-----------------Update un item--------------------------//

updateItem()

function updateItem() {
  const buttons = document.querySelectorAll(".updateItem");
  for (const btn of buttons) {
    btn.removeEventListener("click", updateItemListener); // On retire d'abord l'écouteur d'événement s'il est déjà attaché
    btn.addEventListener("click", updateItemListener);
  }
}


function updateItemListener() {

  const btn = this;
  const categoryId = btn.dataset.id;
  const itemId = itemToEditId

  const inputImageId = "inputImage" + categoryId;

  const itemTitleData = document.querySelector(".inputItemTitle" + categoryId).value
  const itemDescriptionData = document.querySelector(".inputItemDescription" + categoryId).value
  const itemPriceData = document.querySelector(".inputItemPrice" + categoryId).value

  const updateData = new FormData();
  updateData.append("update2", 1);
  updateData.append("itemTitle", itemTitleData);
  updateData.append("itemDescription", itemDescriptionData);
  updateData.append("itemPrice", itemPriceData);
  updateData.append("itemId", itemId);
  updateData.append("categoryId", categoryId);


  if (document.getElementById(inputImageId).files.length > 0) {
    const inputImage = document.getElementById(inputImageId).files[0];
    updateData.append("inputImage", inputImage);
  }

  fetch('index.php?controller=Item&action=edit', {
    method: 'POST',
    body: updateData
  })
    .then(response => response.json())
    .then(data => {

      document.getElementById("addItemButton" + data.categoryId).style.display = 'block';
      document.getElementById("updateItemButton" + data.categoryId).style.display = 'none';

      document.querySelector(".inputItemTitle" + data.categoryId).value = ""
      document.querySelector(".inputItemDescription" + data.categoryId).value = ""
      document.querySelector(".inputItemPrice" + data.categoryId).value = ""

      document.getElementById("itemTitle" + data.itemId).textContent = data.itemTitle
      document.getElementById("itemDescription" + data.itemId).textContent = data.itemDescription
      document.getElementById("itemPrice" + data.itemId).textContent = data.itemPrice

      document.getElementById("inputImage" + data.categoryId).value = "";
      document.getElementById("imgDefault" + data.categoryId).style.display = "block";
      document.getElementById("itemImagePreview" + data.categoryId).setAttribute('src', '');
    });
}
















