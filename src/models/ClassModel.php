<?php

namespace src\models;

use PDO;
use src\core\DatabaseConnection;


/**
 * Gestion de classes et associations d'élèves et profs.
 */
class ClassModel{
    private PDO $db;

    /**
     * @param DatabaseConnection $connection Instance de connexion à la base de données
     */

    public function __construct(private DatabaseConnection $connection)
    {
    }


    /**
     * Permet à l'enseignant de mettre à jour son code de classe.
     * @param int $teacherId Identifiant associé au professeur
     * @param string $nomClasse Nom de la classe
     * @param string $cycle Cycle de la classe
     * @return string Le code de la classe créé
     */
    public function addClass(int $teacherId, string $nomClasse, string $cycle): string
    {
        $character = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterLen = strlen($character);
        do {
            $classCode = '';
            for ($i = 0; $i < 9; $i++) {
                $classCode .= $character[random_int(0, $characterLen - 1)];
            }
            $check = $this->connection->getConnection()->prepare(
                'SELECT 1 FROM classes WHERE classCode = :code LIMIT 1'
            );
            $check->execute([':code' => $classCode]);
        } while ($check->fetchColumn() !== false);

        $sql = "INSERT INTO classes (classCode, name, cycle, teacher_id)
                VALUES (:code, :name, :cycle, :id)";
        $statement = $this->connection->getConnection()->prepare($sql);

        $statement->execute([
            ':code' => $classCode,
            ':name' => $nomClasse,
            ':cycle' => $cycle,
            ':id' => $teacherId
        ]);

        return $classCode;
    }

    /**
     * Récupère les classes d'un enseignant avec leurs élèves.
     * @param int $teacherId Identifiant associé au professeur
     * @return array Renvoie un tableau contenant les classes et leurs élèves
     */
    public function getClassesByTeacher(int $teacherId): array
    {
        $sql = "SELECT c.classCode, c.name, c.cycle, u.username
                FROM classes c
                LEFT JOIN users u
                    ON u.code_classe = c.classCode AND u.role = 'student'
                WHERE c.teacher_id = :teacher_id
                ORDER BY c.name, u.username";
        $statement = $this->connection->getConnection()->prepare($sql);
        $statement->execute([':teacher_id' => $teacherId]);

        $classes = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $classCode = $row['classCode'];
            if (!isset($classes[$classCode])) {
                $classes[$classCode] = [
                    'code' => $classCode,
                    'name' => $row['name'],
                    'cycle' => $row['cycle'],
                    'students' => []
                ];
            }
            if ($row['username'] !== null) {
                $classes[$classCode]['students'][] = $row['username'];
            }
        }

        return array_values($classes);
    }

    /**
     * Récupère le nom de la classe d'un élève.
     * @param int $studentId Identifiant associé à l'élève
     * @return string|null Renvoie le nom de la classe si trouvé, sinon null
     **/
    public function getClassInfo(int $studentId): ?array
    {
        $sql = "SELECT c.name, c.cycle, c.classCode
                FROM classes c JOIN users u ON c.classCode = u.code_classe
                WHERE u.id = :student_id";
        $statement = $this->connection->getConnection()->prepare($sql);

        $statement->execute([':student_id' => $studentId]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Récupère le code de la classe d'un enseignant.
     */
    public function getClassCodeByTeacher(int $teacherId): ?string
    {
        $statement = $this->connection->getConnection()->prepare(
            'SELECT classCode FROM classes WHERE teacher_id = :teacher_id LIMIT 1'
        );
        $statement->execute([':teacher_id' => $teacherId]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result['classCode'] ?? null;
    }

    /**
    * Permet à l'enseignant de créer directement un compte élève rattaché à sa classe.
    * @param int $teacherId Identifiant associé au professeur
    * @param string $pseudo Pseudonyme de l'élève
    * @param string $password Mot de passe de l'élève
    * @param string $codeClasse Code pour rejoindre la classe
    * @return bool True si l'élève a été créé, false sinon
    */
        
    public function createStudent(int $teacherId, string $pseudo, string $password, string $codeClasse): bool
    {
        $classStatement = $this->connection->getConnection()->prepare(
            'SELECT 1 FROM classes WHERE classCode = :code AND teacher_id = :teacher_id LIMIT 1'
        );
        $classStatement->execute([
            ':code' => $codeClasse,
            ':teacher_id' => $teacherId
        ]);
        if ($classStatement->fetchColumn() === false) {
            throw new \RuntimeException('Le code de classe ne correspond pas à cet enseignant.');
        }

        $sql = "INSERT INTO users (username, password, role, code_classe) VALUES (:username, :password, 'student', :code_classe)";
        $statement = $this->connection->getConnection()->prepare($sql);

        return $statement->execute([
            ':username' => $pseudo,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':code_classe' => $codeClasse
        ]);
    }

    /**
     * Nettoie et formate un identifiant unique sous la forme nom.prenom.
     * 
     * @param string $nom Nom de l'élève
     * @param string $prenom Prénom de l'élève
     * @return string Pseudonyme formaté
     */
    public function formatPseudo(string $nom, string $prenom): string
    {
        $chaine = mb_strtolower(trim($nom) . '.' . trim($prenom), 'UTF-8');

        $accents = [
            'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
            'à'=>'a','â'=>'a','ä'=>'a',
            'î'=>'i','ï'=>'i',
            'ô'=>'o','ö'=>'o',
            'ù'=>'u','û'=>'u','ü'=>'u',
            'ç'=>'c'
        ];
        $chaine = strtr($chaine, $accents);

        //Remplace les caractères spéciaux par des '-'
        $chaine = preg_replace('/[\s\'\-]+/', '-', $chaine);

        //Garde les autres caractères restants
        return preg_replace('/[^a-z0-9\.\-]/', '', $chaine);
    }

    /**
     * Importe des élèves à partir d'un fichier CSV et les crée dans la base de données.
     * @param int $teacherId Identifiant associé au professeur
     * @param string $codeClasse Code pour rejoindre la classe
     * @param string $csvFilePath Chemin vers le fichier CSV
     * @return int Nombre d'élèves créés
     */
    public function importStudentsCsv(int $teacherId, string $codeClasse, string $csvFilePath): int
    {
        if (($handle = fopen($csvFilePath, 'r')) === false) {
            throw new Exception("Impossible d'ouvrir le fichier CSV.");
        }

        $pdo = $this->connection->getConnection();

        $classStatement = $pdo->prepare(
            'SELECT 1 FROM classes WHERE classCode = :code AND teacher_id = :teacher_id LIMIT 1'
        );
        $classStatement->execute([
            ':code' => $codeClasse,
            ':teacher_id' => $teacherId
        ]);
        if ($classStatement->fetchColumn() === false) {
            fclose($handle);
            throw new \RuntimeException('Le code de classe ne correspond pas à cet enseignant.');
        }
        
        $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $insertStmt = $pdo->prepare('INSERT INTO users (username, password, role, code_classe) VALUES (?, ?, "student", ?)');

        $password = password_hash($codeClasse, PASSWORD_DEFAULT);
        $count = 0;

        $header = fgets($handle);
        if ($header === false) {
            fclose($handle);
            throw new Exception("Le fichier CSV est vide.");
        }

        $commaColumns = str_getcsv($header, ',');
        $semicolonColumns = str_getcsv($header, ';');
        $delimiter = count($commaColumns) >= 2 ? ',' : ';';

        if (count($commaColumns) < 2 && count($semicolonColumns) < 2) {
            fclose($handle);
            throw new Exception("Le fichier CSV doit contenir les colonnes nom et prenom.");
        }

        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
            $nom = trim($row[0] ?? '');
            $prenom = trim($row[1] ?? '');

            if ($nom !== '' && $prenom !== '') {
                $basePseudo = $this->formatPseudo($nom, $prenom);
                $pseudo = $basePseudo;
                $i = 1;

                // ajoute -$i si doublon
                while ($checkStmt->execute([$pseudo]) && $checkStmt->fetchColumn() > 0) {
                    $i++;
                    $pseudo = "$basePseudo-$i";
                }

                $insertStmt->execute([$pseudo, $password, $codeClasse]);
                $count++;
            }
        }

        fclose($handle);
        return $count;
    }
}