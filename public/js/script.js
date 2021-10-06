function getCratridgeInfo(e,cartridgeSelect, companySelect, printSelect, path) {
    $.ajax({
        type: "POST",
        url: path,
        data: {
            cartridge: e.target.id
        },
        dataType: "json",
        success: function (data) {
            console.log(data)
            cartridgeSelect.val(data[0]);
            printSelect.val(data[1]);
            companySelect.val(data[2]);
        }
    }, "json");
    return false;
}
