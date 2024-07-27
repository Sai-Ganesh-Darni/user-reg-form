<?php
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
?>

<div class = 'center reg-users'>
    <div style = 'display:flex; justify-content:space-between;'>
        <h3 class = '' >Registered Users</h3>
        <a href = '<?= Url::to(['site/form-entry']) ?>' class = 'btn'>Add User</a>
    </div>
    <div>
        <div class = 'users_data'>
            <table class='centered striped '>
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Education Qualification</th>
                        <th>Hobbies</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class = 'users_data'>
                    
                    <?php foreach ($models as $model): ?>
                        <tr>
                            <td><?= $model->id; ?></td>
                            <td><?= $model->first_name.' '.$model->last_name; ?></td>
                            <td><?= $model->email; ?></td>
                            <td><?= $model->phone; ?></td>
                            <td><?= $model->gender; ?></td>
                            <td><?= $model->education_qualification; ?></td>
                            <td><?= $model->hobbies; ?></td>
                            <td><a href='<?= Url::toRoute(['site/get-user/','id' => $model->id]) ?>' class = 'btn'>Edit</a>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
            <?php
                echo LinkPager::widget([
                    'pagination' => $pagination,
                ]);
            ?>
        </div>
    </div>
</div>



