<?php
/**
 * Novel Statistics Admin Page
 */

// Add admin menu
function sid_truyen_add_stats_menu() {
	add_menu_page(
		__( 'Thống kê Truyện', 'sid-truyen' ),
		__( 'Thống kê', 'sid-truyen' ),
		'manage_options',
		'sid-truyen-stats',
		'sid_truyen_stats_page',
		'dashicons-chart-bar',
		25
	);
}
add_action( 'admin_menu', 'sid_truyen_add_stats_menu' );

// Stats page content
function sid_truyen_stats_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'Bạn không có quyền truy cập trang này.', 'sid-truyen' ) );
	}
	
	// Get statistics
	$stats = sid_truyen_get_statistics();
	
	?>
	<div class="wrap">
		<h1><?php _e( 'Thống kê Truyện', 'sid-truyen' ); ?></h1>
		
		<style>
			.stats-grid {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
				gap: 20px;
				margin: 20px 0;
			}
			.stat-card {
				background: #fff;
				border: 1px solid #ccd0d4;
				border-radius: 4px;
				padding: 20px;
				box-shadow: 0 1px 3px rgba(0,0,0,0.1);
			}
			.stat-card h3 {
				margin: 0 0 10px 0;
				color: #646970;
				font-size: 14px;
				font-weight: 400;
				text-transform: uppercase;
			}
			.stat-card .stat-value {
				font-size: 32px;
				font-weight: 600;
				color: #1d2327;
				margin: 10px 0;
			}
			.stat-card .stat-icon {
				width: 40px;
				height: 40px;
				background: #2271b1;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				color: #fff;
				font-size: 20px;
				margin-bottom: 10px;
			}
			.top-novels-table {
				background: #fff;
				border: 1px solid #ccd0d4;
				border-radius: 4px;
				margin: 20px 0;
			}
			.top-novels-table h2 {
				padding: 15px 20px;
				margin: 0;
				border-bottom: 1px solid #ccd0d4;
				font-size: 18px;
			}
			.top-novels-table table {
				width: 100%;
				border-collapse: collapse;
			}
			.top-novels-table th {
				background: #f6f7f7;
				padding: 12px 20px;
				text-align: left;
				font-weight: 600;
				border-bottom: 1px solid #ccd0d4;
			}
			.top-novels-table td {
				padding: 12px 20px;
				border-bottom: 1px solid #f0f0f1;
			}
			.top-novels-table tr:hover {
				background: #f6f7f7;
			}
			.rank-badge {
				display: inline-block;
				width: 30px;
				height: 30px;
				background: #50575e;
				color: #fff;
				border-radius: 50%;
				text-align: center;
				line-height: 30px;
				font-weight: 600;
				font-size: 14px;
			}
			.rank-badge.gold { background: linear-gradient(135deg, #ffd700, #ffed4e); color: #000; }
			.rank-badge.silver { background: linear-gradient(135deg, #c0c0c0, #e8e8e8); color: #000; }
			.rank-badge.bronze { background: linear-gradient(135deg, #cd7f32, #e8a87c); color: #fff; }
			.view-count {
				font-size: 16px;
				font-weight: 600;
				color: #2271b1;
			}
			.progress-bar {
				height: 8px;
				background: #f0f0f1;
				border-radius: 4px;
				overflow: hidden;
				margin-top: 5px;
			}
			.progress-fill {
				height: 100%;
				background: linear-gradient(90deg, #2271b1, #72aee6);
				transition: width 0.3s ease;
			}
		</style>
		
		<!-- Overview Stats -->
		<div class="stats-grid">
			<div class="stat-card">
				<div class="stat-icon">📚</div>
				<h3><?php _e( 'Tổng số truyện', 'sid-truyen' ); ?></h3>
				<div class="stat-value"><?php echo number_format( $stats['total_novels'] ); ?></div>
			</div>
			
			<div class="stat-card">
				<div class="stat-icon">📖</div>
				<h3><?php _e( 'Tổng số chương', 'sid-truyen' ); ?></h3>
				<div class="stat-value"><?php echo number_format( $stats['total_chapters'] ); ?></div>
			</div>
			
			<div class="stat-card">
				<div class="stat-icon">👁️</div>
				<h3><?php _e( 'Tổng lượt xem', 'sid-truyen' ); ?></h3>
				<div class="stat-value"><?php echo number_format( $stats['total_views'] ); ?></div>
			</div>
			
			<div class="stat-card">
				<div class="stat-icon">📈</div>
				<h3><?php _e( 'Trung bình views/truyện', 'sid-truyen' ); ?></h3>
				<div class="stat-value"><?php echo number_format( $stats['avg_views'] ); ?></div>
			</div>
		</div>
		
		<!-- Top Novels -->
		<div class="top-novels-table">
			<h2><?php _e( 'Top 10 Truyện Xem Nhiều Nhất', 'sid-truyen' ); ?></h2>
			<table>
				<thead>
					<tr>
						<th style="width: 60px;"><?php _e( 'Hạng', 'sid-truyen' ); ?></th>
						<th><?php _e( 'Tên truyện', 'sid-truyen' ); ?></th>
						<th><?php _e( 'Tác giả', 'sid-truyen' ); ?></th>
						<th style="width: 100px;"><?php _e( 'Số chương', 'sid-truyen' ); ?></th>
						<th style="width: 150px;"><?php _e( 'Lượt xem', 'sid-truyen' ); ?></th>
						<th style="width: 200px;"><?php _e( 'Phổ biến', 'sid-truyen' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $stats['top_novels'] ) ) : ?>
						<?php foreach ( $stats['top_novels'] as $index => $novel ) : 
							$rank = $index + 1;
							$rank_class = '';
							if ( $rank === 1 ) $rank_class = 'gold';
							elseif ( $rank === 2 ) $rank_class = 'silver';
							elseif ( $rank === 3 ) $rank_class = 'bronze';
							
							$percentage = $stats['total_views'] > 0 ? ( $novel['views'] / $stats['total_views'] ) * 100 : 0;
						?>
							<tr>
								<td>
									<span class="rank-badge <?php echo $rank_class; ?>"><?php echo $rank; ?></span>
								</td>
								<td>
									<strong>
										<a href="<?php echo get_edit_post_link( $novel['id'] ); ?>">
											<?php echo esc_html( $novel['title'] ); ?>
										</a>
									</strong>
								</td>
								<td><?php echo esc_html( $novel['author'] ); ?></td>
								<td style="text-align: center;"><?php echo $novel['chapters']; ?></td>
								<td>
									<span class="view-count">
										<span class="dashicons dashicons-visibility"></span>
										<?php echo number_format( $novel['views'] ); ?>
									</span>
								</td>
								<td>
									<div class="progress-bar">
										<div class="progress-fill" style="width: <?php echo min( 100, $percentage ); ?>%"></div>
									</div>
									<small><?php echo number_format( $percentage, 1 ); ?>%</small>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="6" style="text-align: center; padding: 40px;">
								<?php _e( 'Chưa có dữ liệu thống kê', 'sid-truyen' ); ?>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<!-- Top Chapters -->
		<div class="top-novels-table">
			<h2><?php _e( 'Top 10 Chương Xem Nhiều Nhất', 'sid-truyen' ); ?></h2>
			<table>
				<thead>
					<tr>
						<th style="width: 60px;"><?php _e( 'Hạng', 'sid-truyen' ); ?></th>
						<th><?php _e( 'Tên chương', 'sid-truyen' ); ?></th>
						<th><?php _e( 'Thuộc truyện', 'sid-truyen' ); ?></th>
						<th style="width: 150px;"><?php _e( 'Lượt xem', 'sid-truyen' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty( $stats['top_chapters'] ) ) : ?>
						<?php foreach ( $stats['top_chapters'] as $index => $chapter ) : 
							$rank = $index + 1;
							$rank_class = '';
							if ( $rank === 1 ) $rank_class = 'gold';
							elseif ( $rank === 2 ) $rank_class = 'silver';
							elseif ( $rank === 3 ) $rank_class = 'bronze';
						?>
							<tr>
								<td>
									<span class="rank-badge <?php echo $rank_class; ?>"><?php echo $rank; ?></span>
								</td>
								<td>
									<strong>
										<a href="<?php echo get_edit_post_link( $chapter['id'] ); ?>">
											<?php echo esc_html( $chapter['title'] ); ?>
										</a>
									</strong>
								</td>
								<td><?php echo esc_html( $chapter['novel'] ); ?></td>
								<td>
									<span class="view-count">
										<span class="dashicons dashicons-visibility"></span>
										<?php echo number_format( $chapter['views'] ); ?>
									</span>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="4" style="text-align: center; padding: 40px;">
								<?php _e( 'Chưa có dữ liệu thống kê', 'sid-truyen' ); ?>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
	</div>
	<?php
}

// Get statistics data
function sid_truyen_get_statistics() {
	global $wpdb;
	
	// Total novels
	$total_novels = wp_count_posts( 'novel' );
	$total_novels = $total_novels->publish;
	
	// Total chapters
	$total_chapters = wp_count_posts( 'chapter' );
	$total_chapters = $total_chapters->publish;
	
	// Total views
	$total_views = $wpdb->get_var( 
		"SELECT SUM(CAST(meta_value AS UNSIGNED)) 
		FROM {$wpdb->postmeta} 
		WHERE meta_key = '_sid_novel_views'"
	);
	$total_views = $total_views ? intval( $total_views ) : 0;
	
	// Average views
	$avg_views = $total_novels > 0 ? round( $total_views / $total_novels ) : 0;
	
	// Top 10 novels
	$top_novels = $wpdb->get_results( 
		"SELECT p.ID, p.post_title, pm.meta_value as views
		FROM {$wpdb->posts} p
		INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
		WHERE p.post_type = 'novel' 
		AND p.post_status = 'publish'
		AND pm.meta_key = '_sid_novel_views'
		ORDER BY CAST(pm.meta_value AS UNSIGNED) DESC
		LIMIT 10"
	);
	
	$top_novels_data = array();
	foreach ( $top_novels as $novel ) {
		$author = get_post_meta( $novel->ID, '_sid_novel_author', true );
		$chapter_count = $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_sid_chapter_parent_novel' AND meta_value = %d",
			$novel->ID
		) );
		
		$top_novels_data[] = array(
			'id' => $novel->ID,
			'title' => $novel->post_title,
			'views' => intval( $novel->views ),
			'author' => $author ? $author : __( 'Chưa rõ', 'sid-truyen' ),
			'chapters' => intval( $chapter_count )
		);
	}
	
	// Top 10 chapters
	$top_chapters = $wpdb->get_results( 
		"SELECT p.ID, p.post_title, pm.meta_value as views
		FROM {$wpdb->posts} p
		INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
		WHERE p.post_type = 'chapter' 
		AND p.post_status = 'publish'
		AND pm.meta_key = '_sid_chapter_views'
		ORDER BY CAST(pm.meta_value AS UNSIGNED) DESC
		LIMIT 10"
	);
	
	$top_chapters_data = array();
	foreach ( $top_chapters as $chapter ) {
		$novel_id = get_post_meta( $chapter->ID, '_sid_chapter_parent_novel', true );
		$novel_title = $novel_id ? get_the_title( $novel_id ) : __( 'Chưa rõ', 'sid-truyen' );
		
		$top_chapters_data[] = array(
			'id' => $chapter->ID,
			'title' => $chapter->post_title,
			'views' => intval( $chapter->views ),
			'novel' => $novel_title
		);
	}
	
	return array(
		'total_novels' => $total_novels,
		'total_chapters' => $total_chapters,
		'total_views' => $total_views,
		'avg_views' => $avg_views,
		'top_novels' => $top_novels_data,
		'top_chapters' => $top_chapters_data
	);
}
