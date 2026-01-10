<?php

class ExamDao
{

  private $conn;

  /**
   * constructor of dao class
   */
  public function __construct()
  {
    try {
      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */

      $DB_HOST = "localhost";
      $DB_NAME = 'web-final';
      $DB_USER = 'root';
      $DB_PASSWORD =  '';
      $DB_PORT = 3306;

      /** TODO
       * Create new connection
       */

      $this->conn = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME . ";port=" . $DB_PORT,
      $DB_USER,
      $DB_PASSWORD
      );

      echo "Connected successfully";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  protected function query($sql, $params = []){
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get employees performance report
   */
  public function employees_performance_report() {
    return $this->query("SELECT e.employeeNumber as id,
    CONCAT(e.firstName, ' ', e.lastName) as full_name,
    e.email,
    SUM(p.amount) as total
    FROM employees e
    JOIN customers c ON c.salesRepEmployeeNumber = e.employeeNumber
    JOIN payments p on c.customerNumber = p.customerNumber
    GROUP BY e.employeeNumber, e.firstName, e.lastName, e.email");
  }

  /** TODO
   * Implement DAO method used to delete employee by id
   */
  public function delete_employee($employee_id) {
    $stmt = $this->conn->prepare("DELETE FROM employees WHERE employeeNumber = :employeeNumber");
    return $stmt->execute(["employeeNumber" => $employee_id]);
  }

  /** TODO
   * Implement DAO method used to edit employee data
   */
  public function edit_employee($employee_id, $data) {
    $stmt = $this->conn->prepare("UPDATE employees e SET e.firstName = :firstName, e.lastName = :lastName, e.email = :email WHERE employeeNumber = :employeeNumber");
    return $stmt->execute(["employeeNumber" => $employee_id,
    "firstName" => $data['firstName'],
    "lastName" => $data['lastName'],
    "email" => $data['email']]);
  }

  /** TODO
   * Implement DAO method used to get orders report
   */
  public function get_orders_report() {
    return $this->query("SELECT GROUP_CONCAT(CONCAT('<tr>',
    '<td>', p.productName, '</td>',
    '<td>', od.quantityOrdered, '</td>',
    '<td>', od.priceEach, '</td>'
    '</tr>') SEPARATOR '') as details,
    od.orderNumber as order_number,
    SUM(od.quantityOrdered * od.priceEach) as total_amount
    FROM products p
    JOIN orderdetails od ON p.productNumber = od.productNumber
    GROUP BY od.orderNumber");
  }

  /** TODO
   * Implement DAO method used to get all products in a single order
   */
  public function get_order_details($order_id) {
    return $this->query("SELECT p.productName as product_name,
    od.quantityOrdered as quantity,
    od.priceEach as price_each
    FROM products p
    JOIN orderdetails od ON p.productNumber = od.productNumber
    WHERE od.orderNumber = :orderNumber", ["orderNumber" => $order_id]);
  }
}
