CREATE PROCEDURE `tree_traversal`(
  IN ptask_type VARCHAR(10),
  IN pnode_id INT,
  IN pparent_id INT

)
BEGIN

  DECLARE new_lft, new_rgt, width, has_leafs, superior, superior_parent, old_lft, old_rgt, parent_rgt, subtree_size SMALLINT;

  CASE ptask_type

    WHEN 'insert' THEN

        SELECT rgt INTO new_lft FROM nodes WHERE nodeID = pparent_id;
        UPDATE nodes SET rgt = rgt + 2 WHERE rgt >= new_lft;
        UPDATE nodes SET lft = lft + 2 WHERE lft > new_lft;
        INSERT INTO nodes (lft, rgt, parentID) VALUES (new_lft, (new_lft + 1), pparent_id);
		SELECT LAST_INSERT_ID();

    WHEN 'delete' THEN

        SELECT lft, rgt, (rgt - lft), (rgt - lft + 1), parentID
		  INTO new_lft, new_rgt, has_leafs, width, superior_parent
		  FROM nodes WHERE nodeID = pnode_id;

		DELETE FROM nodes WHERE nodeID = pnode_id;

        IF (has_leafs = 1) THEN
          DELETE FROM nodes WHERE lft BETWEEN new_lft AND new_rgt;
          UPDATE nodes SET rgt = rgt - width WHERE rgt > new_rgt;
          UPDATE nodes SET lft = lft - width WHERE lft > new_rgt;
        ELSE
          DELETE FROM nodes WHERE lft = new_lft;
          UPDATE nodes SET rgt = rgt - 1, lft = lft - 1, parentID = superior_parent
		   WHERE lft BETWEEN new_lft AND new_rgt;
          UPDATE nodes SET rgt = rgt - 2 WHERE rgt > new_rgt;
          UPDATE nodes SET lft = lft - 2 WHERE lft > new_rgt;
        END IF;

    WHEN 'move' THEN

		IF (pnode_id != pparent_id) THEN
        CREATE TEMPORARY TABLE IF NOT EXISTS working_tree_map
    (
        `nodeID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
          `lft` smallint(5) unsigned DEFAULT NULL,
          `rgt` smallint(5) unsigned DEFAULT NULL,
          `parentID` smallint(5) unsigned NOT NULL,
          PRIMARY KEY (`nodeID`)
        );

        INSERT INTO working_tree_map (nodeID, lft, rgt, parentID)
			 SELECT t1.nodeID,
					(t1.lft - (SELECT MIN(lft) FROM nodes WHERE nodeID = pnode_id)) AS lft,
					(t1.rgt - (SELECT MIN(lft) FROM nodes WHERE nodeID = pnode_id)) AS rgt,
					t1.parentID
			   FROM nodes AS t1, nodes AS t2
			  WHERE t1.lft BETWEEN t2.lft AND t2.rgt
    AND t2.nodeID = pnode_id;

        DELETE FROM nodes WHERE nodeID IN (SELECT nodeID FROM working_tree_map);

        SELECT rgt INTO parent_rgt FROM nodes WHERE nodeID = pparent_id;
        SET subtree_size = (SELECT (MAX(rgt) + 1) FROM working_tree_map);

        UPDATE nodes
          SET lft = (CASE WHEN lft > parent_rgt THEN lft + subtree_size ELSE lft END),
              rgt = (CASE WHEN rgt >= parent_rgt THEN rgt + subtree_size ELSE rgt END)
        WHERE rgt >= parent_rgt;

        INSERT INTO nodes (nodeID, lft, rgt, parentID)
             SELECT nodeID, lft + parent_rgt, rgt + parent_rgt, parentID
               FROM working_tree_map;

        UPDATE nodes
           SET lft = (SELECT COUNT(*) FROM vw_lftrgt AS v WHERE v.lft <= nodes.lft),
               rgt = (SELECT COUNT(*) FROM vw_lftrgt AS v WHERE v.lft <= nodes.rgt);

        DELETE FROM working_tree_map;
        UPDATE nodes SET parentID = pparent_id WHERE nodeID = pnode_id;
		END IF;

    WHEN 'order' THEN

        SELECT lft, rgt, (rgt - lft + 1), parentID INTO old_lft, old_rgt, width, superior
          FROM nodes WHERE nodeID = pnode_id;

        SELECT parentID INTO superior_parent FROM nodes WHERE nodeID = pparent_id;

        IF (superior = superior_parent) THEN
          SELECT (rgt + 1) INTO new_lft FROM nodes WHERE nodeID = pparent_id;
        ELSE
          SELECT (lft + 1) INTO new_lft FROM nodes WHERE nodeID = pparent_id;
        END IF;

	    IF (new_lft != old_lft) THEN
		  CREATE TEMPORARY TABLE IF NOT EXISTS working_tree_map
    (
        `nodeID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
          `lft` smallint(5) unsigned DEFAULT NULL,
          `rgt` smallint(5) unsigned DEFAULT NULL,
          `parentID` smallint(5) unsigned NOT NULL,
          PRIMARY KEY (`nodeID`)
        );

	     INSERT INTO working_tree_map (nodeID, lft, rgt, parentID)
            SELECT t1.nodeID,
			  	   (t1.lft - (SELECT MIN(lft) FROM nodes WHERE nodeID = pnode_id)) AS lft,
				   (t1.rgt - (SELECT MIN(lft) FROM nodes WHERE nodeID = pnode_id)) AS rgt,
				   t1.parentID
			  FROM nodes AS t1, nodes AS t2
			 WHERE t1.lft BETWEEN t2.lft AND t2.rgt AND t2.nodeID = pnode_id;

       DELETE FROM nodes WHERE nodeID IN (SELECT nodeID FROM working_tree_map);

       IF(new_lft < old_lft) THEN
          UPDATE nodes SET lft = lft + width WHERE lft >= new_lft AND lft < old_lft;
          UPDATE nodes SET rgt = rgt + width WHERE rgt > new_lft AND rgt < old_rgt;
          UPDATE working_tree_map SET lft = new_lft + lft, rgt = new_lft + rgt;
       END IF;

       IF(new_lft > old_lft) THEN
            UPDATE nodes SET lft = lft - width WHERE lft > old_lft AND lft < new_lft;
            UPDATE nodes SET rgt = rgt - width WHERE rgt > old_rgt AND rgt < new_lft;
            UPDATE working_tree_map SET lft = (new_lft - width) + lft, rgt = (new_lft - width) + rgt;
       END IF;

       INSERT INTO nodes (nodeID, lft, rgt, parentID)
            SELECT nodeID, lft, rgt, parentID
              FROM working_tree_map;

       DELETE FROM working_tree_map;
	   END IF;
  END CASE;

END