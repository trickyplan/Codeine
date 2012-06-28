$.tools.validator.fn("[data-equals]", "Значения не совпадают", function(input) {
    var name = input.attr("data-equals"),
    field = this.getInputs().filter("[name=" + name + "]");
    return input.val() == field.val() ? true : [name];
});