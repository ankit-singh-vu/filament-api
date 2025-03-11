<?php if (!empty($students)): ?>
    <table border="1" cellspacing="0" cellpadding="10">
        <?php foreach ($students as $student ): ?>
            <tr>
                    <td><?= $student->id ?></td>
                    <td><?= $student->name ?></td>
                    <td><?= $student->age ?></td>
                    <td><?= $student->email ?></td>
                    <td><?= $student->created_at ?></td>

            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No students found.</p>
<?php endif; ?>
