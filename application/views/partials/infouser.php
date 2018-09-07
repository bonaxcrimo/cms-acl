<?php
    @$query=("SELECT * from tbluser where userpk=".$userpk);
    @$dataUser=queryCustom($query);
?>
    <table class="table tableBesuk">
        <thead>
            <tr>
                <th colspan="4">Data Info Member</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>userid : </th>
                <td><?= @$dataUser->userid ?></td>
                <th>username :</th>
                <td><?= @$dataUser->username ?></td>
            </tr>
        </tbody>
    </table>