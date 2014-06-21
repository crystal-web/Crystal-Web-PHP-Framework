<?php
set_time_limit(1);
ini_set("max_execution_time", 1);
Class MinecraftStats {
	public function get($server, $port) {
		noError(true);
		$socket = @stream_socket_client(sprintf('tcp://%s:%u', $server, $port), $errno, $errstr, 1);
		//$socket = @file_get_contents_curl(sprintf('tcp://%s:%u', $server, $port), $errno, $errstr, 1);
		noError(false);
		$stats = new stdClass;
		$stats->is_online = false;

		if (!$socket)
			return $stats;

		fwrite($socket, "\xfe\x01");
		$data = fread($socket, 1024);
		fclose($socket);
		
		// Is this a disconnect with the ping?
		if($data == false AND substr($data, 0, 1) != "\xFF") 
			return $stats;

		
		$data = substr($data, 9);
		$data = htmlspecialchars($data);
		$data = mb_convert_encoding($data, 'auto', 'UCS-2');
		$data = explode("\x00", $data);
		
		$stats->is_online = true;
		
		list($stats->protocol_version, $stats->game_version, $stats->motd, $stats->online_players, $stats->max_players) = $data;
		$stats->motd = Encoding::toUTF8($stats->motd);
		return $stats;
	}
}