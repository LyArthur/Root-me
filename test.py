import uuid
import datetime

def modify_uuidv1_date(uuid_str, new_date):
    """
    Modify the date in a UUIDv1

    :param uuid_str: The UUIDv1 string to modify
    :param new_date: The new date as a datetime object
    :return: The modified UUIDv1 string
    """
    uuid_obj = uuid.UUID(uuid_str, version=1)
    timestamp_nanoseconds = int(new_date.timestamp() * 1e9)
    timestamp_nanoseconds -= timestamp_nanoseconds % 10
    uuid_obj.time = (timestamp_nanoseconds >> 32) & 0xFFFFFFFF, (timestamp_nanoseconds & 0xFFFFFFFF)
    return str(uuid_obj)

# Example usage
original_uuid = "b82940de-e75a-11ee-94ff-0242ac110006"
new_date = datetime.datetime(2024, 3, 21,8,12,11)
modified_uuid = modify_uuidv1_date(original_uuid, new_date)
print(modified_uuid)
