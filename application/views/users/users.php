<table class="data-table" cellpadding="5">
	<tbody>
		<tr><td colspan='10'><a href="<?php echo HTTP_PATH; ?>users/add">[+] Add User</a></td></tr>
		<tr>
			<th style="width:150px">Email</th>
			<th style="width:150px">Username</th>
			<th style="width:350px">Full Name</th>
			<th style="width:100px">Manage</th>
		</tr>
		
		<?php
		if(isset($users)) {
			foreach($users as $a) {
				$id				=	$a->id;
				$email			=	$a->email;
				$username		=	$a->username;
				$firstname		=	$a->first_name;
				$middlename		=	$a->middle_name;
				$lastname		=	$a->last_name;
				$managelinks	=	"<a href='".HTTP_PATH."users/edit/$id'>[&Delta;] Edit</a><hr/>
				<a href='".HTTP_PATH."users/delete/$id'>[&Omega;] Delete</a>";
				
				echo "<tr>";
					echo "<td>$email</td>";
					echo "<td>$username</td>";
					echo "<td>$firstname $middlename $lastname</td>";
					echo "<td>$managelinks</td>";
				echo "</tr>";
			}
		}
		?>
		
	</tbody>
</table>