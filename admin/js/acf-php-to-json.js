var copyJson = document.querySelector(".copy-json");

if (copyJson) {
  copyJson.addEventListener("click", function (event) {
    event.preventDefault();

    var jsonOutput = document.querySelector(".json-output");

    if (jsonOutput) {
      var toClipboard = jsonOutput.innerHTML;
      var dummyTextarea = document.createElement("textarea");
      document.body.appendChild(dummyTextarea);
      dummyTextarea.value = toClipboard;
      dummyTextarea.select();
      document.execCommand("copy");
      document.body.removeChild(dummyTextarea);
    }
  });
}
