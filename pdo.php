<?php
class Database {
    private $host = "localhost";
    private $db_name = "school-ROC";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    public function selectData() {
        $query = "SELECT * FROM students";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function selectStudentById($student_id) {
        $query = "SELECT * FROM students WHERE student_id = :student_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function updateStudent($student_id, $name, $age, $email) {
        $query = "UPDATE students SET name = :name, age = :age, email = :email WHERE student_id = :student_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':student_id', $student_id);

        if ($stmt->execute()) {
            echo "Student updated successfully.";
        } else {
            echo "Error updating student.";
        }
    }

    public function deleteStudent($student_id) {
        $query = "DELETE FROM students WHERE student_id = :student_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);

        if ($stmt->execute()) {
            echo "Student deleted successfully.";
        } else {
            echo "Error deleting student.";
        }
    }
}

$database = new Database();
$conn = $database->getConnection();

$conn->exec("
    INSERT INTO students (name, age, email) VALUES
    ('Helena', 20, 'helena@example.com'),
    ('Godrine', 22, 'godrine@example.com'),
    ('Nana', 19, 'nana@example.com'),
    ('Inesh', 21, 'inesh@example.com'),
    ('Keshav', 23, 'keshav@example.com');
");

$database->updateStudent(1, 'Helena Updated', 21, 'helena.updated@example.com');

$database->deleteStudent(2);

print_r($database->selectData());
?>
