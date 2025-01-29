CREATE OR REPLACE FUNCTION public.filter_user_notifications()
    RETURNS void
    LANGUAGE plpgsql
AS $function$
BEGIN
    UPDATE user_notifications un
    SET status = CASE
        WHEN un.status = 'for send' AND u.fcm_token IS NULL THEN 'send later'
        WHEN un.status = 'send later' AND u.fcm_token IS NOT NULL THEN 'for send'
        ELSE un.status
    END
    FROM users u
    WHERE u.id = un.user_id
      AND (
          (un.status = 'for send' AND u.fcm_token IS NULL)
          OR
          (un.status = 'send later' AND u.fcm_token IS NOT NULL)
    );
    
    RETURN;
END;
$function$