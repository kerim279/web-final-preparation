var EmployeesService = {
    delete_employee: function(employee_id) {
        if (
          confirm(
            "Do you want to delete employee with the id " + employee_id + "?"
          ) == true
        ) {
          console.log("TODO Perform deletion logic");
        }
    },
    edit_employee: function(employee_id){
        console.log("Get employee with provided id, open modal and populate modal fields with data returned from the database");
        alert("Opened");
    }
}